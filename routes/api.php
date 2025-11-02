<?php

use App\Http\Controllers\API\LoketController;
use App\Http\Controllers\API\AntrianController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Routes untuk interaksi frontend/API
|
*/

// Loket endpoints (public atau bisa ditambahkan auth jika diperlukan)
Route::apiResource('lokets', LoketController::class);

// Antrian endpoints
Route::post('lokets/{loket}/antrians', [AntrianController::class, 'createForLoket'])->name('api.antrians.create');
Route::get('lokets/{loket}/waiting', [AntrianController::class, 'waitingForLoket'])->name('api.antrians.waiting');
Route::patch('antrians/{antrian}/status', [AntrianController::class, 'updateStatus'])->name('api.antrians.updateStatus');
// Get currently called antrians (optional: ?loket_id=1 for specific loket)
Route::get('antrians/current', [AntrianController::class, 'currentCalled'])->name('api.antrians.current');

// Get all antrians dengan filter (akan mengembalikan JSON)
Route::get('antrians', function (Request $request) {
    try {
        $query = \App\Models\Antrian::with('loket')->orderByDesc('created_at');

        if ($request->has('loket_id')) {
            $query->where('loket_id', $request->loket_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $antrians = $query->paginate($request->get('per_page', 20));

        return response()->json($antrians);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Gagal memuat antrian',
            'error' => $e->getMessage()
        ], 500);
    }
})->name('api.antrians.index');

// Return waiting counts grouped by loket for today
Route::get('lokets/waiting-counts', function (Request $request) {
    try {
        $today = \Carbon\Carbon::today();

        $lokets = \App\Models\Loket::withCount(['antrians as waiting_count' => function ($q) use ($today) {
            $q->where('status', 'menunggu')->whereDate('created_at', $today);
        }])->get();

        return response()->json([
            'message' => 'Berhasil mengambil waiting counts per loket',
            'data' => $lokets->map(function ($l) {
                return [
                    'loket_id' => $l->id,
                    'nama_loket' => $l->nama_loket,
                    'code' => $l->code,
                    'waiting_count' => $l->waiting_count
                ];
            })
        ]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Gagal mengambil waiting counts', 'error' => $e->getMessage()], 500);
    }
})->name('api.lokets.waiting_counts');
