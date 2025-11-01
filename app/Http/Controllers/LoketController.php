<?php

namespace App\Http\Controllers;

use App\Models\Loket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $lokets = Loket::orderBy('nama_loket')->get();
            return view('lokets.index', compact('lokets'));
        } catch (\Exception $e) {
            Log::error('Error loading lokets: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat daftar loket.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lokets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'nullable|string|max:10|unique:lokets,code',
                'nama_loket' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            Loket::create($validated);

            return redirect()->route('lokets.index')
                ->with('success', 'Loket berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating loket: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan loket: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Loket $loket)
    {
        try {
            $loket->load('antrians');
            return view('lokets.show', compact('loket'));
        } catch (\Exception $e) {
            Log::error('Error showing loket: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat detail loket.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loket $loket)
    {
        return view('lokets.edit', compact('loket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loket $loket)
    {
        try {
            $validated = $request->validate([
                'code' => 'nullable|string|max:10|unique:lokets,code,' . $loket->id,
                'nama_loket' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            $loket->update($validated);

            return redirect()->route('lokets.index')
                ->with('success', 'Loket berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating loket: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui loket: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loket $loket)
    {
        try {
            // Cek apakah ada antrian terkait
            if ($loket->antrians()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus loket karena masih memiliki antrian.');
            }

            $loket->delete();

            return redirect()->route('lokets.index')
                ->with('success', 'Loket berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting loket: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus loket: ' . $e->getMessage());
        }
    }
}

