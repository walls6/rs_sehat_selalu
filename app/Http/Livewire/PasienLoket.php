<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Loket;
use App\Models\Antrian;
use App\Services\AntrianService;

class PasienLoket extends Component
{
    public $lokets = [];
    public $selectedLoket = null;
    public $currentCalled = [];
    public $myAntrian = null;
    public $showSuccess = false;
    public $successMessage = '';
    public $waitingCounts = [];

    protected $listeners = ['refreshDisplay' => 'loadCurrentCalled'];

    public function mount()
    {
        $this->lokets = Loket::all();
        $this->loadCurrentCalled();
        $this->loadWaitingCounts();
        
        // Load myAntrian from session if exists
        $antrianId = session('my_antrian_id');
        if ($antrianId) {
            try {
                $this->myAntrian = Antrian::with('loket')->find($antrianId);
                // If antrian not found or already completed, clear session
                // Tapi tetap tampilkan jika status 'dipanggil' atau 'menunggu'
                if (!$this->myAntrian || $this->myAntrian->status === 'selesai') {
                    session()->forget('my_antrian_id');
                    $this->myAntrian = null;
                }
            } catch (\Exception $e) {
                session()->forget('my_antrian_id');
                $this->myAntrian = null;
            }
        }
    }

    public function loadWaitingCounts()
    {
        // Optimize: Single query instead of N queries (N+1 problem fix)
        if (empty($this->lokets)) {
            $this->waitingCounts = [];
            return;
        }
        
        $loketIds = collect($this->lokets)->pluck('id')->toArray();
        
        // Single query to get all counts at once
        $counts = Antrian::whereIn('loket_id', $loketIds)
            ->where('status', 'menunggu')
            ->selectRaw('loket_id, COUNT(*) as count')
            ->groupBy('loket_id')
            ->pluck('count', 'loket_id')
            ->toArray();
        
        // Initialize all lokets with 0, then update with actual counts
        foreach ($this->lokets as $loket) {
            $this->waitingCounts[$loket->id] = $counts[$loket->id] ?? 0;
        }
    }

    public function loadCurrentCalled()
    {
        try {
            $service = new AntrianService();
            $called = $service->currentCalled();
            
            // Remove duplicates and ensure unique by loket_id (only show one per loket - most recent)
            $uniqueCalled = $called->groupBy('loket_id')
                ->map(function ($group) {
                    return $group->sortByDesc('waktu_panggil')->first();
                })
                ->values();
            
            // Convert to array format that Livewire can handle (only if changed to avoid unnecessary updates)
            $newCalled = $uniqueCalled->map(function ($item) {
                return [
                    'id' => $item->id,
                    'loket_id' => $item->loket_id,
                    'nomor_antrian' => $item->nomor_antrian,
                    'waktu_panggil' => $item->waktu_panggil ? $item->waktu_panggil->toDateTimeString() : null,
                    'loket' => [
                        'id' => $item->loket->id ?? null,
                        'nama_loket' => $item->loket->nama_loket ?? 'Loket',
                        'code' => $item->loket->code ?? '',
                    ]
                ];
            })->toArray();
            
            $this->currentCalled = $newCalled;
            
            // Only reload waiting counts if lokets are loaded
            if (!empty($this->lokets)) {
                $this->loadWaitingCounts();
            }
        } catch (\Exception $e) {
            // Silently fail to avoid breaking the UI
            \Log::error('Error in loadCurrentCalled: ' . $e->getMessage());
        }
    }

    public function takeAntrian($loketId)
    {
        try {
            // Validasi loket ID
            if (!$loketId) {
                session()->flash('error', 'Loket tidak valid');
                return;
            }

            $loket = Loket::findOrFail($loketId);
            
            $service = new AntrianService();
            $antrian = $service->createForLoket($loket);
            
            $this->myAntrian = $antrian->load('loket');
            $this->showSuccess = true;
            $this->successMessage = "Antrian berhasil diambil! Nomor antrian Anda: {$antrian->loket->code}{$antrian->nomor_antrian}";
            
            // Simpan ID antrian di session agar tidak hilang setelah refresh/cetak
            session(['my_antrian_id' => $antrian->id]);
            
            // Refresh display (loadCurrentCalled already calls loadWaitingCounts)
            $this->loadCurrentCalled();
            $this->dispatch('refreshDisplay');
            
            session()->flash('success', $this->successMessage);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('error', 'Loket tidak ditemukan');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengambil antrian: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Only reload lokets if empty (avoid unnecessary queries)
        if (empty($this->lokets)) {
            $this->lokets = Loket::all();
            $this->loadWaitingCounts();
        }
        
        // Reload myAntrian from session if it's null (after polling or refresh)
        if (!$this->myAntrian) {
            $antrianId = session('my_antrian_id');
            if ($antrianId) {
                try {
                    $this->myAntrian = Antrian::with('loket')->find($antrianId);
                    // If antrian not found or already completed, clear session
                    if (!$this->myAntrian || in_array($this->myAntrian->status, ['selesai'])) {
                        session()->forget('my_antrian_id');
                        $this->myAntrian = null;
                    }
                } catch (\Exception $e) {
                    session()->forget('my_antrian_id');
                    $this->myAntrian = null;
                }
            }
        }
        
        // Ensure currentCalled doesn't have duplicates by loket_id
        if (!empty($this->currentCalled)) {
            $this->currentCalled = collect($this->currentCalled)
                ->unique('loket_id')
                ->values()
                ->all();
        }
        
        return view('livewire.pasien-loket')
            ->layout('layouts.app');
    }
}

