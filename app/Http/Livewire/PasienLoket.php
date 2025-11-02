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
    }

    public function loadWaitingCounts()
    {
        foreach ($this->lokets as $loket) {
            $this->waitingCounts[$loket->id] = Antrian::where('loket_id', $loket->id)
                ->where('status', 'menunggu')
                ->count();
        }
    }

    public function loadCurrentCalled()
    {
        $service = new AntrianService();
        $this->currentCalled = $service->currentCalled();
        $this->loadWaitingCounts();
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
            
            // Refresh display
            $this->loadCurrentCalled();
            $this->loadWaitingCounts();
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
        // Reload data before rendering
        if (empty($this->lokets)) {
            $this->lokets = Loket::all();
        }
        $this->loadWaitingCounts();
        
        return view('livewire.pasien-loket')
            ->layout('layouts.app');
    }
}

