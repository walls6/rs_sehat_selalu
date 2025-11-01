<?php
namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\Loket;
use App\Models\Antrian;
use Carbon\Carbon;

class PetugasLoket extends Component {
    public $loket_id;
    public $lokets;
    public $waiting = [];
    public $called = null;

    protected $listeners = ['refreshList' => '$refresh'];

    public function mount() {
        $this->lokets = Loket::all();
    }

    public function updatedLoketId() {
        $this->loadLists();
    }

    public function loadLists() {
        if(!$this->loket_id) { $this->waiting = []; $this->called = null; return; }
        $this->waiting = Antrian::where('loket_id',$this->loket_id)->where('status','menunggu')->orderBy('created_at')->get();
        $this->called = Antrian::where('loket_id',$this->loket_id)->where('status','dipanggil')->orderByDesc('waktu_panggil')->first();
    }

    public function callNow($antrianId) {
        // set all dipanggil for this loket to menunggu? normally keep history. Here we set chosen to dipanggil
        $an = Antrian::findOrFail($antrianId);
        $an->status = 'dipanggil';
        $an->waktu_panggil = Carbon::now();
        $an->save();
        $this->loadLists();
        // dispatch event agar display update
        $this->dispatch('refreshDisplay');
    }

    public function finish($antrianId) {
        $an = Antrian::findOrFail($antrianId);
        $an->status = 'selesai';
        $an->save();
        $this->loadLists();
        $this->dispatch('refreshDisplay');
    }

    public function render() {
        $this->loadLists();
        return view('livewire.petugas-loket');
    }
}
