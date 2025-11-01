<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Loket;
use App\Models\Antrian;
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
        'antrian-created' => 'handleAntrianCreated'
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
        } catch (\Exception $e) {
            $this->lokets = collect([]);
            Log::error('Error loading lokets: ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat daftar loket. Silakan refresh halaman.');
        }
    }

    /**
     * Handle event ketika antrian baru dibuat
     */
    public function handleAntrianCreated($data)
    {
        // Jika loket yang dipilih sesuai dengan antrian baru, refresh list
        if (isset($data['loket_id']) && $this->loket_id == $data['loket_id']) {
            $this->loadLists();
            session()->flash('info', 'Antrian baru tersedia!');
        }
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
        if (!$this->loket_id) {
            $this->waiting = [];
            $this->called = null;
            return;
        }
        
        try {
            // Ambil antrian yang berstatus 'menunggu' untuk loket ini
            // Diurutkan dari yang paling awal (created_at ASC = yang pertama datang)
            $this->waiting = Antrian::where('loket_id', $this->loket_id)
                ->where('status', 'menunggu')
                ->with('loket')
                ->orderBy('created_at', 'asc') // Urut dari yang paling awal
                ->get();
            
            // Ambil antrian yang sedang 'dipanggil' untuk loket ini
            // Ambil yang paling terakhir dipanggil (waktu_panggil DESC)
            $this->called = Antrian::where('loket_id', $this->loket_id)
                ->where('status', 'dipanggil')
                ->with('loket')
                ->orderByDesc('waktu_panggil')
                ->first(); // Hanya ambil 1 yang terakhir dipanggil
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
            $an = Antrian::findOrFail($antrianId);
            
            // Validasi: Pastikan antrian masih dalam status 'menunggu'
            if ($an->status !== 'menunggu') {
                session()->flash('error', 'Antrian ini sudah tidak dalam status menunggu.');
                $this->loadLists();
                return;
            }
            
            // Validasi: Pastikan antrian ini milik loket yang dipilih
            if ($an->loket_id != $this->loket_id) {
                session()->flash('error', 'Antrian ini bukan milik loket yang dipilih.');
                $this->loadLists();
                return;
            }
            
            // Update status menggunakan DB transaction
            DB::beginTransaction();
            try {
                // Update status menjadi 'dipanggil'
                $an->status = 'dipanggil';
                // Isi kolom waktu_panggil dengan waktu sekarang
                $an->waktu_panggil = Carbon::now();
                $an->save();
                
                // Verifikasi update
                $an->refresh();
                if ($an->status !== 'dipanggil') {
                    throw new \Exception('Gagal mengupdate status antrian ke dipanggil.');
                }
                
                if (!$an->waktu_panggil) {
                    throw new \Exception('Kolom waktu_panggil tidak terisi.');
                }
                
                DB::commit();
                
                Log::info('Antrian dipanggil', [
                    'antrian_id' => $an->id,
                    'nomor_antrian' => $an->nomor_antrian,
                    'loket_id' => $an->loket_id,
                    'waktu_panggil' => $an->waktu_panggil->format('Y-m-d H:i:s')
                ]);
                
                // Refresh lists agar antrian hilang dari 'menunggu' dan muncul di 'dipanggil'
                $this->loadLists();
                
                // Dispatch event untuk refresh display layar plasma
                $this->dispatch('refreshDisplay');
                
                session()->flash('success', 'Nomor antrian ' . ($an->loket->code ?? '') . $an->nomor_antrian . ' telah dipanggil!');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
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
            $an = Antrian::findOrFail($antrianId);
            
            // Validasi: Pastikan antrian sedang dalam status 'dipanggil'
            if ($an->status !== 'dipanggil') {
                session()->flash('error', 'Antrian ini tidak sedang dipanggil.');
                $this->loadLists();
                return;
            }
            
            // Validasi: Pastikan antrian ini milik loket yang dipilih
            if ($an->loket_id != $this->loket_id) {
                session()->flash('error', 'Antrian ini bukan milik loket yang dipilih.');
                $this->loadLists();
                return;
            }
            
            // Update status menggunakan DB transaction
            DB::beginTransaction();
            try {
                // Update status menjadi 'selesai'
                $an->status = 'selesai';
                $an->save();
                
                // Verifikasi update
                $an->refresh();
                if ($an->status !== 'selesai') {
                    throw new \Exception('Gagal mengupdate status antrian ke selesai.');
                }
                
                DB::commit();
                
                Log::info('Antrian selesai', [
                    'antrian_id' => $an->id,
                    'nomor_antrian' => $an->nomor_antrian,
                    'loket_id' => $an->loket_id,
                    'waktu_selesai' => now()->format('Y-m-d H:i:s')
                ]);
                
                // Refresh lists agar antrian hilang dari 'Sedang Dipanggil'
                $this->loadLists();
                
                // Dispatch event untuk refresh display layar plasma
                $this->dispatch('refreshDisplay');
                
                session()->flash('success', 'Antrian ' . ($an->loket->code ?? '') . $an->nomor_antrian . ' telah diselesaikan.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
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

