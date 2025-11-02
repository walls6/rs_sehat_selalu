<?php

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\LoketController;
use App\Http\Controllers\AntrianController;

// Login route - redirect to Google OAuth
Route::get('/login', function () {
    return redirect('/auth/google');
})->name('login');

Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');

Route::get('auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->user();

        // cari atau buat user
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'password' => bcrypt(Str::random(16))
            ]
        );

        Auth::login($user);

        return redirect('/petugas'); // halaman petugas
    } catch (\Exception $e) {
        return redirect('/')->with('error', 'Gagal login: ' . $e->getMessage());
    }
})->name('auth.google.callback');

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/petugas');
    }
    return view('welcome');
})->name('home');

// Route Public untuk Pasien
Route::get('/pasien', \App\Http\Livewire\PasienLoket::class)->name('pasien');

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Dashboard Petugas Loket
    Route::get('/petugas', \App\Http\Livewire\PetugasLoket::class)->name('petugas');
    
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
        Auth::logout();
        return redirect('/')->with('success', 'Anda telah logout.');
    })->name('logout');
});
