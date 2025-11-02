<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Loket;
use App\Models\Antrian;
use App\Services\AntrianService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PetugasLoket extends Component
{
    public $loket_id;
    public $lokets;
    public $waiting = [];
    public $called = null;

    protected $listeners = [
        'refreshList' => '$refresh',
        'antrian-created' => 'handleAntrianCreated',
        // when display requests refresh, reload lists
        'refreshDisplay' => 'loadLists'
    ];

    /**
     * Mount - Pastikan hanya petugas yang sudah login via Gmail yang bisa akses
     * Middleware auth sudah di-handle di route, tapi kita cek juga di sini
     */
    public function mount()
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            abort(403, 'Hanya petugas yang sudah login yang bisa mengakses halaman ini.');
        }
        
        try {
            // Load semua loket yang tersedia untuk dipilih petugas
            $this->lokets = Loket::orderBy('nama_loket')->get();
            // Load initial lists for petugas dashboard
            $this->loadLists();
        } catch (\Exception $e) {
            $this->lokets = collect([]);
            Log::error('Error loading lokets: ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat daftar loket. Silakan refresh halaman.');
        }
    }

    /**
     * Handle event ketika antrian baru dibuat
     */
    public function handleAntrianCreated($data = null)
    {
        // Refresh lists whenever a new antrian is created (show all lokets)
        $this->loadLists();
        session()->flash('info', 'Antrian baru tersedia!');
    }

    public function updatedLoketId()
    {
        $this->loadLists();
    }

    /**
     * Load daftar antrian menunggu dan yang sedang dipanggil untuk loket yang dipilih
     * Antrian menunggu diurutkan dari yang paling awal (created_at ASC)
     */
    public function loadLists()
    {
        try {
            // Load waiting antrians across all lokets (today only)
            $today = Carbon::today();
            $this->waiting = Antrian::where('status', 'menunggu')
                ->whereDate('created_at', $today)
                ->with('loket')
                ->orderBy('created_at', 'asc')
                ->get();

            // Load the most recently called antrian across all lokets (today only)
            $calledCollection = Antrian::where('status', 'dipanggil')
                ->whereDate('created_at', $today)
                ->with('loket')
                ->orderByDesc('waktu_panggil')
                ->get();

            $this->called = $calledCollection->first() ?: null;

            // Debug/log counts to help verify data is loaded
            Log::debug('Petugas loadLists counts', ['waiting' => $this->waiting->count(), 'called' => $calledCollection->count()]);
        } catch (\Exception $e) {
            Log::error('Error loading antrian lists: ' . $e->getMessage());
            $this->waiting = collect([]);
            $this->called = null;
        }
    }

    /**
     * Panggil antrian - Update status ke 'dipanggil' dan isi waktu_panggil
     * Antrian akan hilang dari daftar 'menunggu' dan muncul di 'Sedang Dipanggil'
     */
    public function callNow($antrianId)
    {
        try {
            $service = new AntrianService();
            $an = Antrian::findOrFail($antrianId);

            // Validations
            if ($an->status !== 'menunggu') {
                session()->flash('error', 'Antrian ini sudah tidak dalam status menunggu.');
                $this->loadLists();
                return;
            }

            // Use service to update status (service sets waktu_panggil)
            $updated = $service->updateStatus($an, 'dipanggil');

            Log::info('Antrian dipanggil', [
                'antrian_id' => $updated->id,
                'nomor_antrian' => $updated->nomor_antrian,
                'loket_id' => $updated->loket_id,
                'waktu_panggil' => $updated->waktu_panggil ? $updated->waktu_panggil->format('Y-m-d H:i:s') : null
            ]);

            $this->loadLists();

            // Notify display
            try {
                if (method_exists($this, 'emit')) {
                    $this->emit('refreshDisplay');
                } else {
                    $this->dispatchBrowserEvent('refreshDisplay');
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to emit/dispatch refreshDisplay: ' . $e->getMessage());
            }

            session()->flash('success', 'Nomor antrian ' . ($updated->loket->code ?? '') . $updated->nomor_antrian . ' telah dipanggil!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Antrian not found: ' . $antrianId);
            session()->flash('error', 'Antrian tidak ditemukan.');
            $this->loadLists();
        } catch (\Exception $e) {
            Log::error('Error calling antrian: ' . $e->getMessage(), [
                'antrian_id' => $antrianId,
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Gagal memanggil antrian: ' . substr($e->getMessage(), 0, 100));
            $this->loadLists();
        }
    }

    /**
     * Selesaikan antrian - Update status ke 'selesai'
     * Antrian akan hilang dari daftar 'Sedang Dipanggil'
     */
    public function finish($antrianId)
    {
        try {
            $service = new AntrianService();
            $an = Antrian::findOrFail($antrianId);

            // Validasi: Pastikan antrian sedang dalam status 'dipanggil'
            if ($an->status !== 'dipanggil') {
                session()->flash('error', 'Antrian ini tidak sedang dipanggil.');
                $this->loadLists();
                return;
            }

            // no loket ownership check: petugas manages all queues

            $updated = $service->updateStatus($an, 'selesai');

            Log::info('Antrian selesai', [
                'antrian_id' => $updated->id,
                'nomor_antrian' => $updated->nomor_antrian,
                'loket_id' => $updated->loket_id,
                'waktu_selesai' => now()->format('Y-m-d H:i:s')
            ]);

            $this->loadLists();

            try {
                if (method_exists($this, 'emit')) {
                    $this->emit('refreshDisplay');
                } else {
                    $this->dispatchBrowserEvent('refreshDisplay');
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to emit/dispatch refreshDisplay: ' . $e->getMessage());
            }

            session()->flash('success', 'Antrian ' . ($updated->loket->code ?? '') . $updated->nomor_antrian . ' telah diselesaikan.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Antrian not found: ' . $antrianId);
            session()->flash('error', 'Antrian tidak ditemukan.');
            $this->loadLists();
        } catch (\Exception $e) {
            Log::error('Error finishing antrian: ' . $e->getMessage(), [
                'antrian_id' => $antrianId,
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Gagal menyelesaikan antrian: ' . substr($e->getMessage(), 0, 100));
            $this->loadLists();
        }
    }

    public function render()
    {
        $this->loadLists();
        return view('livewire.petugas-loket');
    }
}

