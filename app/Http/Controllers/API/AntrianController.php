<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Loket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AntrianController extends Controller
{
    /**
     * Create antrian for a specific loket (auto-generate nomor)
     */
    public function createForLoket(Request $request, Loket $loket)
    {
        try {
            // Auto-generate nomor antrian jika tidak disediakan
            $nomorAntrian = $request->input('nomor_antrian');
            
            if (!$nomorAntrian) {
                $today = Carbon::today();
                
                // Cek jumlah antrian hari ini untuk loket ini
                $countToday = Antrian::where('loket_id', $loket->id)
                    ->whereDate('created_at', $today)
                    ->count();

                // Format nomor: code + nomor urut (001, 002, dst)
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
                }
            } else {
                // Validasi nomor jika disediakan manual
                $request->validate([
                    'nomor_antrian' => 'required|string|max:50',
                ]);
            }

            $antrian = Antrian::create([
                'loket_id' => $loket->id,
                'nomor_antrian' => $nomorAntrian,
                'status' => 'menunggu',
            ]);

            return response()->json([
                'message' => 'Antrian berhasil dibuat',
                'data' => $antrian->load('loket')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat antrian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get waiting antrians for a specific loket
     */
    public function waitingForLoket(Loket $loket)
    {
        try {
            $waiting = Antrian::where('loket_id', $loket->id)
                ->where('status', 'menunggu')
                ->orderBy('created_at')
                ->get();

            return response()->json([
                'message' => 'Berhasil mengambil antrian menunggu',
                'data' => $waiting
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil antrian menunggu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update antrian status
     */
    public function updateStatus(Request $request, Antrian $antrian)
    {
        try {
            $data = $request->validate([
                'status' => 'required|in:menunggu,dipanggil,selesai',
            ]);

            $antrian->status = $data['status'];
            
            if ($data['status'] === 'dipanggil') {
                $antrian->waktu_panggil = Carbon::now();
            }

            $antrian->save();

            return response()->json([
                'message' => 'Status antrian berhasil diperbarui',
                'data' => $antrian->load('loket')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui status antrian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get currently called antrians (all lokets or specific loket)
     */
    public function currentCalled(Request $request)
    {
        try {
            $query = Antrian::where('status', 'dipanggil')
                ->with('loket')
                ->orderBy('waktu_panggil', 'desc');

            // Filter by loket_id if provided
            if ($request->has('loket_id')) {
                $query->where('loket_id', $request->loket_id);
            }

            $called = $query->get();

            return response()->json([
                'message' => 'Berhasil mengambil antrian yang dipanggil',
                'data' => $called,
                'count' => $called->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil antrian yang dipanggil',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

