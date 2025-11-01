<?php

// app/Http/Controllers/API/LoketController.php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Loket;
use Illuminate\Http\Request;

class LoketController extends Controller {
    public function index() {
        return response()->json(Loket::all());
    }

    public function store(Request $req) {
        $data = $req->validate(['code'=>'nullable|string','nama_loket'=>'required','deskripsi'=>'nullable']);
        $loket = Loket::create($data);
        return response()->json($loket,201);
    }

    public function show(Loket $loket) {
        return response()->json($loket);
    }

    public function update(Request $req, Loket $loket) {
        $loket->update($req->only(['code','nama_loket','deskripsi']));
        return response()->json($loket);
    }

    public function destroy(Loket $loket) {
        $loket->delete();
        return response()->json(null,204);
    }
}
