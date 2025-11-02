<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Loket;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\AntrianService;

class AntrianController extends Controller
{
    /**
     * Create antrian for a specific loket (auto-generate nomor)
     */
    public function createForLoket(Request $request, Loket $loket)
    {
        try {
            $service = new AntrianService();
            $antrian = $service->createForLoket($loket, $request->input('nomor_antrian'));

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
            $service = new AntrianService();
            $waiting = $service->waitingForLoket($loket);

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
            $service = new AntrianService();
            $updated = $service->updateStatus($antrian, $data['status']);

            return response()->json([
                'message' => 'Status antrian berhasil diperbarui',
                'data' => $updated->load('loket')
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

