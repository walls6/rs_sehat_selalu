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

    protected $listeners = ['refreshList' => '$refresh', 'antrian-dipanggil' => 'handleAntrianDipanggil'];

    public function mount()
    {
        $this->lokets = Loket::all();
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
        // Load all antrians regardless of loket selection for admin view
        $this->waiting = Antrian::where('status', 'menunggu')
            ->with('loket')
            ->orderBy('created_at')
            ->get();
        
        $this->called = Antrian::where('status', 'dipanggil')
            ->with('loket')
            ->orderByDesc('waktu_panggil')
            ->first();
    }

    public function callNow($antrianId)
    {
        try {
            $antrian = Antrian::findOrFail($antrianId);
            
            // Use service directly instead of API call
            $service = new AntrianService();
            $updated = $service->updateStatus($antrian, 'dipanggil');

            session()->flash('success', 'Antrian berhasil dipanggil');
            $this->loadLists();
            $this->dispatch('refreshDisplay');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memanggil antrian: ' . $e->getMessage());
        }
    }

    public function finish($antrianId)
    {
        try {
            $antrian = Antrian::findOrFail($antrianId);
            
            // Use service directly instead of API call
            $service = new AntrianService();
            $updated = $service->updateStatus($antrian, 'selesai');

            session()->flash('success', 'Antrian berhasil diselesaikan');
            $this->loadLists();
            $this->dispatch('refreshDisplay');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyelesaikan antrian: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $this->loadLists();
        return view('livewire.petugas-loket')
            ->layout('layouts.app');
    }
}

