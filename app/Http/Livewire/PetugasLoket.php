<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Loket;
use App\Models\Antrian;
use App\Services\AntrianService;

class PetugasLoket extends Component
{
    public $loket_id = null;
    public $lokets;
    public $waiting = [];
    public $called = null;
    public $categories = ['Pendaftaran Umum', 'Poli Anak', 'Poli Dalam', 'Poli Gigi', 'Farmasi'];
    public $calledByLoket = [];
    public $waitingByLoket = [];

    protected $listeners = ['refreshList' => '$refresh', 'antrian-dipanggil' => 'handleAntrianDipanggil'];
    
    public $lastRefresh = null;

    public function mount()
    {
        $this->loadLists();
    }

    public function updatedLoketId()
    {
        $this->loadLists();
    }

    public function handleAntrianDipanggil($data)
    {
        // Refresh lists when antrian is called
        $this->loadLists();
    }

    public function loadLists()
    {
        try {
            // Load 5 specific lokets for grid display (only once, cache if empty)
            if (empty($this->lokets)) {
                $desiredOrder = $this->categories;
                $orderIndex = array_flip($desiredOrder);
                $this->lokets = Loket::whereIn('nama_loket', $desiredOrder)
                    ->get()
                    ->sortBy(function ($loket) use ($orderIndex) {
                        return $orderIndex[$loket->nama_loket] ?? PHP_INT_MAX;
                    })
                    ->values();
            }

            $loketIds = $this->lokets->pluck('id')->toArray();
            
            // Optimized: Single query untuk semua data sekaligus dengan eager loading
            // Load all antrians in one go, then filter in memory
            $allAntrians = Antrian::whereIn('loket_id', $loketIds)
                ->whereIn('status', ['menunggu', 'dipanggil'])
                ->with('loket')
                ->get();
            
            // Separate waiting and called in memory (much faster than multiple queries)
            $waitingGrouped = $allAntrians->where('status', 'menunggu')
                ->sortBy('created_at')
                ->groupBy('loket_id');
            
            $calledGrouped = $allAntrians->where('status', 'dipanggil')
                ->sortByDesc('waktu_panggil')
                ->groupBy('loket_id');
            
            // Build maps for grid cards
            $this->calledByLoket = [];
            $this->waitingByLoket = [];
            foreach ($this->lokets as $loket) {
                $this->calledByLoket[$loket->id] = $calledGrouped->get($loket->id)?->first();
                $this->waitingByLoket[$loket->id] = $waitingGrouped->get($loket->id) ?? collect();
            }
            
            // Load overall waiting list (limit untuk performa)
            $this->waiting = $allAntrians->where('status', 'menunggu')
                ->sortBy('created_at')
                ->take(100) // Limit untuk performa
                ->values();
            
            // Load most recent called overall
            $this->called = $allAntrians->where('status', 'dipanggil')
                ->sortByDesc('waktu_panggil')
                ->first();
                
        } catch (\Exception $e) {
            \Log::error('Error in loadLists: ' . $e->getMessage());
            $this->waiting = collect();
            $this->called = null;
            $this->calledByLoket = [];
            $this->waitingByLoket = [];
        }
    }

    public function callNow($antrianId)
    {
        try {
            $antrian = Antrian::with('loket')->findOrFail($antrianId);
            
            // Use service directly instead of API call
            $service = new AntrianService();
            $service->updateStatus($antrian, 'dipanggil');

            session()->flash('success', 'Antrian berhasil dipanggil');
            
            // Reload lists after action
            $this->loadLists();
            $this->dispatch('refreshDisplay');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memanggil antrian: ' . $e->getMessage());
        }
    }

    public function finish($antrianId)
    {
        try {
            $antrian = Antrian::with('loket')->findOrFail($antrianId);
            $loketId = $antrian->loket_id;
            
            // Use service directly instead of API call
            $service = new AntrianService();
            $service->updateStatus($antrian, 'selesai');

            // Auto-call next waiting number for the same loket (optimized query)
            $next = Antrian::where('loket_id', $loketId)
                ->where('status', 'menunggu')
                ->with('loket')
                ->orderBy('created_at')
                ->first();
                
            if ($next) {
                $service->updateStatus($next, 'dipanggil');
                session()->flash('success', 'Antrian diselesaikan. Memanggil berikutnya: ' . $next->loket->code . $next->nomor_antrian);
            } else {
                session()->flash('success', 'Antrian berhasil diselesaikan. Tidak ada antrian menunggu.');
            }
            
            // Reload lists after action
            $this->loadLists();
            $this->dispatch('refreshDisplay');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyelesaikan antrian: ' . $e->getMessage());
        }
    }

    public function skip($antrianId)
    {
        try {
            $antrian = Antrian::with('loket')->findOrFail($antrianId);
            $loketId = $antrian->loket_id;

            // Kembalikan ke antrian menunggu dan letakkan di belakang antrean
            $antrian->status = 'menunggu';
            $antrian->waktu_panggil = null;
            $antrian->created_at = now();
            $antrian->save();

            // Panggil nomor berikutnya (optimized query)
            $next = Antrian::where('loket_id', $loketId)
                ->where('status', 'menunggu')
                ->with('loket')
                ->orderBy('created_at')
                ->first();
                
            if ($next) {
                $service = new AntrianService();
                $service->updateStatus($next, 'dipanggil');
                session()->flash('success', 'Nomor dilewati sementara. Memanggil berikutnya: ' . $next->loket->code . $next->nomor_antrian);
            } else {
                session()->flash('success', 'Nomor dilewati. Tidak ada antrian menunggu.');
            }

            // Reload lists after action
            $this->loadLists();
            $this->dispatch('refreshDisplay');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal melakukan skip: ' . $e->getMessage());
        }
    }

    public function refreshData()
    {
        // Lightweight refresh untuk polling - hanya reload data yang berubah
        try {
            if (empty($this->lokets)) {
                $this->loadLists();
                return;
            }
            
            $loketIds = $this->lokets->pluck('id')->toArray();
            
            // Single optimized query untuk refresh
            $allAntrians = Antrian::whereIn('loket_id', $loketIds)
                ->whereIn('status', ['menunggu', 'dipanggil'])
                ->with('loket')
                ->get();
            
            // Update in memory (faster than full reload)
            $waitingGrouped = $allAntrians->where('status', 'menunggu')
                ->sortBy('created_at')
                ->groupBy('loket_id');
            
            $calledGrouped = $allAntrians->where('status', 'dipanggil')
                ->sortByDesc('waktu_panggil')
                ->groupBy('loket_id');
            
            // Update maps
            foreach ($this->lokets as $loket) {
                $this->calledByLoket[$loket->id] = $calledGrouped->get($loket->id)?->first();
                $this->waitingByLoket[$loket->id] = $waitingGrouped->get($loket->id) ?? collect();
            }
            
            // Update overall lists (limit untuk performa)
            $this->waiting = $allAntrians->where('status', 'menunggu')
                ->sortBy('created_at')
                ->take(100)
                ->values();
            
            $this->called = $allAntrians->where('status', 'dipanggil')
                ->sortByDesc('waktu_panggil')
                ->first();
                
            $this->lastRefresh = now();
        } catch (\Exception $e) {
            \Log::error('Error in refreshData: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Only load lokets if empty (avoid reloading on every render/poll)
        if (empty($this->lokets)) {
            $desiredOrder = $this->categories;
            $orderIndex = array_flip($desiredOrder);
            $this->lokets = Loket::whereIn('nama_loket', $desiredOrder)
                ->get()
                ->sortBy(function ($loket) use ($orderIndex) {
                    return $orderIndex[$loket->nama_loket] ?? PHP_INT_MAX;
                })
                ->values();
        }
        
        // Don't reload lists on every render - only when explicitly called
        // This prevents timeout during polling
        return view('livewire.petugas-loket')
            ->layout('layouts.app');
    }
}

