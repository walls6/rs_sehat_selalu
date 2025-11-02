<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\LoketController;
use App\Http\Controllers\AntrianController;

// Route untuk halaman login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Route untuk Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Route Public (tidak perlu auth)
Route::get('/', function () {
    return view('pasien-antrian-page');
})->name('pasien.index');
Route::get('/display', \App\Livewire\DisplayAntrian::class)->name('display.index');

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Dashboard Petugas Loket
    Route::get('/petugas', \App\Livewire\PetugasLoket::class)->name('petugas.dashboard');
    
    // Manajemen Loket (CRUD)
    Route::resource('lokets', LoketController::class);
    
    // Manajemen Antrian
    Route::prefix('antrians')->name('antrians.')->group(function () {
        Route::get('/', [AntrianController::class, 'index'])->name('index');
        Route::post('/lokets/{loket}/generate', [AntrianController::class, 'generate'])->name('generate');
        Route::patch('/{antrian}/status', [AntrianController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{antrian}', [AntrianController::class, 'destroy'])->name('destroy');
    });
    
    // Route logout
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/login')->with('success', 'Anda telah logout.');
    })->name('logout');
});
