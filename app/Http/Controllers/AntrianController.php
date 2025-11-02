<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Loket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\AntrianService;
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
            $service = new AntrianService();
            $antrian = $service->createForLoket($loket);

            return redirect()->back()
                ->with('success', 'Nomor antrian ' . $antrian->nomor_antrian . ' berhasil dibuat untuk loket ' . $loket->nama_loket);
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
            $service = new AntrianService();
            $service->updateStatus($antrian, $validated['status']);

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

