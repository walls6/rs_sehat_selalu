<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Antrian;
use Illuminate\Support\Facades\Log;

class DisplayAntrian extends Component
{
    public $called = [];

    protected $listeners = ['refreshDisplay' => 'loadCalled'];

    public function mount()
    {
        $this->loadCalled();
    }

    public function loadCalled()
    {
        try {
            $this->called = Antrian::where('status', 'dipanggil')
                ->with('loket')
                ->orderBy('waktu_panggil', 'desc')
                ->limit(10) // Limit untuk performa
                ->get();
        } catch (\Exception $e) {
            Log::error('Error loading called antrians: ' . $e->getMessage());
            $this->called = collect([]);
        }
    }

    public function render()
    {
        $this->loadCalled();
        return view('livewire.display-antrian');
    }
}

