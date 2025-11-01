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
     * Create antrian for a specific loket
     */
    public function createForLoket(Request $request, Loket $loket)
    {
        $data = $request->validate([
            'nomor_antrian' => 'required|string',
        ]);

        $antrian = Antrian::create([
            'loket_id' => $loket->id,
            'nomor_antrian' => $data['nomor_antrian'],
            'status' => 'menunggu',
        ]);

        return response()->json($antrian, 201);
    }

    /**
     * Get waiting antrians for a specific loket
     */
    public function waitingForLoket(Loket $loket)
    {
        $waiting = Antrian::where('loket_id', $loket->id)
            ->where('status', 'menunggu')
            ->orderBy('created_at')
            ->get();

        return response()->json($waiting);
    }

    /**
     * Update antrian status
     */
    public function updateStatus(Request $request, Antrian $antrian)
    {
        $data = $request->validate([
            'status' => 'required|in:menunggu,dipanggil,selesai',
        ]);

        $antrian->status = $data['status'];
        
        if ($data['status'] === 'dipanggil') {
            $antrian->waktu_panggil = Carbon::now();
        }

        $antrian->save();

        return response()->json($antrian);
    }

    /**
     * Get currently called antrians
     */
    public function currentCalled()
    {
        $called = Antrian::where('status', 'dipanggil')
            ->with('loket')
            ->orderBy('waktu_panggil', 'desc')
            ->get();

        return response()->json($called);
    }
}

