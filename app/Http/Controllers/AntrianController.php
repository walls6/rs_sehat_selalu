<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Loket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AntrianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Antrian::with('loket')->orderByDesc('created_at');

            // Filter by loket
            if ($request->has('loket_id')) {
                $query->where('loket_id', $request->loket_id);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $antrians = $query->paginate(20);
            $lokets = Loket::orderBy('nama_loket')->get();

            return view('antrians.index', compact('antrians', 'lokets'));
        } catch (\Exception $e) {
            Log::error('Error loading antrians: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat daftar antrian.');
        }
    }

    /**
     * Generate nomor antrian untuk loket tertentu
     */
    public function generate(Request $request, Loket $loket)
    {
        try {
            // Generate nomor antrian otomatis berdasarkan loket
            $today = Carbon::today();
            
            // Cek jumlah antrian hari ini untuk loket ini
            $countToday = Antrian::where('loket_id', $loket->id)
                ->whereDate('created_at', $today)
                ->count();

            // Format nomor: code + nomor urut (001, 002, dst)
            $nomorUrut = str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);
            $nomorAntrian = ($loket->code ?? '') . $nomorUrut;

            // Pastikan nomor unik (jika ada duplikasi, tambahkan increment)
            $counter = 1;
            while (Antrian::where('loket_id', $loket->id)
                ->where('nomor_antrian', $nomorAntrian)
                ->whereDate('created_at', $today)
                ->exists()) {
                $nomorUrut = str_pad($countToday + $counter + 1, 3, '0', STR_PAD_LEFT);
                $nomorAntrian = ($loket->code ?? '') . $nomorUrut;
                $counter++;
            }

            $antrian = Antrian::create([
                'loket_id' => $loket->id,
                'nomor_antrian' => $nomorAntrian,
                'status' => 'menunggu',
            ]);

            return redirect()->back()
                ->with('success', 'Nomor antrian ' . $nomorAntrian . ' berhasil dibuat untuk loket ' . $loket->nama_loket);
        } catch (\Exception $e) {
            Log::error('Error generating antrian: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal membuat nomor antrian: ' . $e->getMessage());
        }
    }

    /**
     * Update status antrian
     */
    public function updateStatus(Request $request, Antrian $antrian)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:menunggu,dipanggil,selesai',
            ]);

            $antrian->status = $validated['status'];

            if ($validated['status'] === 'dipanggil') {
                $antrian->waktu_panggil = Carbon::now();
            } elseif ($validated['status'] === 'selesai') {
                // Opsional: bisa tambahkan waktu_selesai jika diperlukan
            }

            $antrian->save();

            return redirect()->back()
                ->with('success', 'Status antrian berhasil diperbarui menjadi: ' . $validated['status']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error updating antrian status: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status antrian: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Antrian $antrian)
    {
        try {
            $nomorAntrian = $antrian->nomor_antrian;
            $antrian->delete();

            return redirect()->back()
                ->with('success', 'Antrian ' . $nomorAntrian . ' berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting antrian: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus antrian: ' . $e->getMessage());
        }
    }
}

