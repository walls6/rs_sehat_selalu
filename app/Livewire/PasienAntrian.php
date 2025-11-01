<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Loket;
use App\Models\Antrian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PasienAntrian extends Component
{
    public $lokets = [];
    public $selected_loket_id = null;
    public $nomor_antrian = null;
    public $antrian_terakhir = null;
    public $show_success = false;

    protected $listeners = ['refreshDisplay' => '$refresh'];

    public function mount()
    {
        try {
            // Cek database connection dulu
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                Log::error('Database connection error in mount: ' . $e->getMessage());
                $this->lokets = collect([]);
                session()->flash('error', 'Koneksi database gagal. Silakan hubungi administrator.');
                return;
            }

            $this->lokets = Loket::orderBy('nama_loket')->get();
            
            if ($this->lokets->isEmpty()) {
                session()->flash('info', 'Belum ada loket tersedia. Silakan hubungi administrator untuk menambahkan loket.');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $this->lokets = collect([]);
            Log::error('Database query error loading lokets: ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat daftar loket. Silakan hubungi administrator.');
        } catch (\Exception $e) {
            $this->lokets = collect([]);
            Log::error('Error loading lokets: ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat daftar loket.');
        }
    }

    public function ambilAntrian()
    {
        try {
            // Validasi manual dengan error handling yang lebih baik
            if (!$this->selected_loket_id) {
                $this->addError('selected_loket_id', 'Silakan pilih loket terlebih dahulu.');
                return;
            }

            // Cek database connection
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                Log::error('Database connection error: ' . $e->getMessage());
                $this->addError('selected_loket_id', 'Koneksi database gagal. Silakan hubungi administrator.');
                session()->flash('error', 'Koneksi database gagal. Silakan hubungi administrator.');
                return;
            }

            // Cek apakah loket exists
            $loket = Loket::find($this->selected_loket_id);
            if (!$loket) {
                $this->addError('selected_loket_id', 'Loket yang dipilih tidak ditemukan.');
                session()->flash('error', 'Loket yang dipilih tidak ditemukan. Silakan pilih loket lain.');
                return;
            }

            // Generate nomor antrian otomatis
            $today = Carbon::today();
            
            try {
                $countToday = Antrian::where('loket_id', $loket->id)
                    ->whereDate('created_at', $today)
                    ->count();

                $nomorUrut = str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);
                $nomorAntrian = ($loket->code ?? '') . $nomorUrut;

                // Pastikan nomor unik
                $counter = 1;
                while (Antrian::where('loket_id', $loket->id)
                    ->where('nomor_antrian', $nomorAntrian)
                    ->whereDate('created_at', $today)
                    ->exists()) {
                    $nomorUrut = str_pad($countToday + $counter + 1, 3, '0', STR_PAD_LEFT);
                    $nomorAntrian = ($loket->code ?? '') . $nomorUrut;
                    $counter++;
                    
                    // Safety limit
                    if ($counter > 100) {
                        throw new \Exception('Tidak dapat menghasilkan nomor antrian unik.');
                    }
                }

                // Create antrian menggunakan DB transaction untuk memastikan data tersimpan
                DB::beginTransaction();
                try {
                    // Pastikan loket_id valid dan terhubung dengan benar
                    if (!$loket->id || !Loket::where('id', $loket->id)->exists()) {
                        throw new \Exception('Loket tidak valid atau tidak ditemukan di database.');
                    }

                    // Create antrian dengan relationship ke loket
                    $antrian = Antrian::create([
                        'loket_id' => $loket->id,
                        'nomor_antrian' => $nomorAntrian,
                        'status' => 'menunggu',
                    ]);

                    // Commit transaction - PENTING: Data harus commit dulu sebelum redirect
                    DB::commit();

                    // Verifikasi data tersimpan dengan query langsung ke database
                    $savedAntrian = DB::table('antrians')
                        ->where('id', $antrian->id)
                        ->first();
                    
                    if (!$savedAntrian) {
                        throw new \Exception('Data antrian tidak tersimpan ke database.');
                    }

                    // Verifikasi relationship dengan loket
                    $verifyLoket = DB::table('antrians')
                        ->where('id', $antrian->id)
                        ->where('loket_id', $loket->id)
                        ->first();
                    
                    if (!$verifyLoket) {
                        throw new \Exception('Relationship antara antrian dan loket tidak valid.');
                    }

                    // Reload model dengan relationship menggunakan Eloquent
                    $antrianModel = Antrian::with('loket')->find($antrian->id);
                    if (!$antrianModel || !$antrianModel->loket) {
                        throw new \Exception('Gagal memuat relationship dengan loket.');
                    }
                    
                    $savedAntrian = $antrianModel;

                    // Set properties untuk UI
                    $this->nomor_antrian = $nomorAntrian;
                    $this->antrian_terakhir = $savedAntrian;
                    $this->show_success = true;
                    $this->selected_loket_id = null;

                    // Log success
                    Log::info('Antrian berhasil dibuat', [
                        'antrian_id' => $savedAntrian->id,
                        'nomor_antrian' => $nomorAntrian,
                        'loket_id' => $loket->id,
                        'loket_nama' => $loket->nama_loket,
                    ]);

                    // Dispatch event untuk refresh display dan petugas dashboard
                    $this->dispatch('refreshDisplay');
                    $this->dispatch('antrian-created', [
                        'antrian_id' => $savedAntrian->id,
                        'loket_id' => $loket->id
                    ]);

                    // Verifikasi final: Pastikan data benar-benar ada di database sebelum redirect
                    // Query langsung ke database untuk memastikan data tersimpan
                    $finalCheck = DB::table('antrians')
                        ->where('id', $savedAntrian->id)
                        ->where('loket_id', $loket->id)
                        ->where('nomor_antrian', $nomorAntrian)
                        ->where('status', 'menunggu')
                        ->exists();
                    
                    if (!$finalCheck) {
                        throw new \Exception('Verifikasi akhir gagal. Data mungkin tidak tersimpan dengan benar.');
                    }

                    // Verifikasi foreign key constraint - pastikan loket_id valid di tabel lokets
                    $loketExists = DB::table('lokets')
                        ->where('id', $loket->id)
                        ->exists();
                    
                    if (!$loketExists) {
                        throw new \Exception('Loket dengan ID ' . $loket->id . ' tidak ditemukan di database.');
                    }

                    // Log informasi lengkap untuk debugging
                    Log::info('Antrian berhasil dibuat dan tersimpan di database', [
                        'antrian_id' => $savedAntrian->id,
                        'nomor_antrian' => $nomorAntrian,
                        'loket_id' => $loket->id,
                        'loket_nama' => $loket->nama_loket,
                        'status' => 'menunggu',
                        'created_at' => $savedAntrian->created_at,
                        'database_verified' => true,
                    ]);

                    // Simpan info antrian ke session untuk ditampilkan di display
                    session()->flash('nomor_antrian_baru', $nomorAntrian);
                    session()->flash('loket_nama_baru', $loket->nama_loket);
                    session()->flash('antrian_id_baru', $savedAntrian->id);
                    session()->flash('success', 'Nomor antrian ' . $nomorAntrian . ' berhasil diambil untuk ' . $loket->nama_loket . '. Silakan perhatikan layar display untuk panggilan Anda.');
                    
                    // Dispatch event untuk JavaScript redirect
                    // Delay 1 detik untuk memastikan semua proses selesai dan data tersimpan
                    $this->dispatch('redirect-to-display');
                } catch (\Exception $e) {
                    // Rollback transaction jika ada error
                    DB::rollBack();
                    throw $e;
                }
            } catch (\Illuminate\Database\QueryException $e) {
                Log::error('Database query error: ' . $e->getMessage(), [
                    'sql' => $e->getSql(),
                    'bindings' => $e->getBindings()
                ]);
                $this->addError('selected_loket_id', 'Terjadi kesalahan pada database. Silakan coba lagi.');
                session()->flash('error', 'Terjadi kesalahan pada database. Silakan coba lagi atau hubungi administrator.');
            } catch (\Exception $e) {
                Log::error('Error creating antrian: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                    'loket_id' => $this->selected_loket_id
                ]);
                $this->addError('selected_loket_id', 'Gagal mengambil nomor antrian. Silakan coba lagi.');
                session()->flash('error', 'Gagal mengambil nomor antrian: ' . substr($e->getMessage(), 0, 100));
            }
        } catch (\Exception $e) {
            Log::error('Fatal error in ambilAntrian: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('selected_loket_id', 'Terjadi kesalahan. Silakan refresh halaman dan coba lagi.');
            session()->flash('error', 'Terjadi kesalahan. Silakan refresh halaman dan coba lagi.');
        }
    }

    public function resetForm()
    {
        $this->selected_loket_id = null;
        $this->nomor_antrian = null;
        $this->antrian_terakhir = null;
        $this->show_success = false;
        $this->mount(); // Reload lokets
    }

    public function render()
    {
        try {
            // Pastikan lokets selalu ada walaupun empty
            if (!is_array($this->lokets) && !is_object($this->lokets)) {
                $this->lokets = collect([]);
            }
            
            return view('livewire.pasien-antrian');
        } catch (\Exception $e) {
            Log::error('Error rendering PasienAntrian view: ' . $e->getMessage());
            // Fallback: return simple error view
            return <<<'HTML'
                <div class="container mx-auto p-6">
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <p class="font-bold">Terjadi kesalahan. Silakan refresh halaman.</p>
                    </div>
                </div>
            HTML;
        }
    }
}

