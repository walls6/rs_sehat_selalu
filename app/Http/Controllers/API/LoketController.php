<?php

// app/Http/Controllers/API/LoketController.php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Loket;
use Illuminate\Http\Request;

class LoketController extends Controller {
    public function index() {
        try {
            $lokets = Loket::orderBy('nama_loket')->get();
            return response()->json([
                'message' => 'Berhasil mengambil data loket',
                'data' => $lokets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data loket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $req) {
        try {
            $data = $req->validate([
                'code' => 'nullable|string|max:10|unique:lokets,code',
                'nama_loket' => 'required|string|max:255',
                'deskripsi' => 'nullable|string'
            ]);
            
            $loket = Loket::create($data);
            
            return response()->json([
                'message' => 'Loket berhasil dibuat',
                'data' => $loket
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat loket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Loket $loket) {
        try {
            $loket->load('antrians');
            return response()->json([
                'message' => 'Berhasil mengambil detail loket',
                'data' => $loket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil detail loket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $req, Loket $loket) {
        try {
            $data = $req->validate([
                'code' => 'nullable|string|max:10|unique:lokets,code,' . $loket->id,
                'nama_loket' => 'required|string|max:255',
                'deskripsi' => 'nullable|string'
            ]);
            
            $loket->update($data);
            
            return response()->json([
                'message' => 'Loket berhasil diperbarui',
                'data' => $loket
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui loket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Loket $loket) {
        try {
            // Cek apakah ada antrian terkait
            if ($loket->antrians()->count() > 0) {
                return response()->json([
                    'message' => 'Tidak dapat menghapus loket karena masih memiliki antrian',
                    'error' => 'Loket memiliki ' . $loket->antrians()->count() . ' antrian terkait'
                ], 422);
            }

            $loket->delete();
            
            return response()->json([
                'message' => 'Loket berhasil dihapus'
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus loket',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
