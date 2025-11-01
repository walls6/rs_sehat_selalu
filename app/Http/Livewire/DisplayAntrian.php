<?php

namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\Antrian;

class DisplayAntrian extends Component {
    public $called = [];

    protected $listeners = ['refreshDisplay' => 'loadCalled'];

    public function mount() {
        $this->loadCalled();
    }

    public function loadCalled() {
        $this->called = Antrian::where('status','dipanggil')->with('loket')->orderBy('waktu_panggil','desc')->get();
    }

    public function render() {
        $this->loadCalled();
        return view('livewire.display-antrian');
    }
}
