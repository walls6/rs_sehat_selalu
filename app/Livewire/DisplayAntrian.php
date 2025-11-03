<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Antrian;
use App\Models\Loket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class DisplayAntrian extends Component
{
    public Collection $called;
    public Collection $lokets;

    public function mount()
    {
        $this->called = collect([]);
        $this->lokets = collect([]);
        $this->loadData();
    }

    public function loadCalled()
    {
        try {
            $this->called = Antrian::where('status', 'dipanggil')
                ->with('loket')
                ->orderBy('waktu_panggil', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error loading called antrians: ' . $e->getMessage());
            $this->called = collect([]);
        }
    }

    public function loadLokets()
    {
        try {
            $this->lokets = Loket::orderBy('nama_loket')->get();
        } catch (\Exception $e) {
            Log::error('Error loading lokets: ' . $e->getMessage());
            $this->lokets = collect([]);
        }
    }

    public function loadData()
    {
        $this->loadCalled();
        $this->loadLokets();
        // Notify browser that display has updated (consumed by layout script)
        try {
            $this->dispatch('display-updated', timestamp: time());
        } catch (\Throwable $e) {
            // Swallow dispatch errors to avoid breaking render cycle
        }
    }

    public function render()
    {
        try {
            $this->loadData();
            return view('livewire.display-antrian')
                ->extends('layouts.display')
                ->section('content');
        } catch (\Exception $e) {
            Log::error('Error in DisplayAntrian render: ' . $e->getMessage());
            return view('livewire.display-antrian')
                ->extends('layouts.display')
                ->section('content');
        }
    }

    public $listeners = [
        // If broadcasting (Echo) is configured, you can add:
        // 'echo:antrian,AntrianUpdated' => 'loadData',
        'refreshComponent' => '$refresh'
    ];
}

