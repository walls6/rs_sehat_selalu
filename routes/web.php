<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\LoketController;
use App\Http\Controllers\AntrianController;

<<<<<<< HEAD
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

Route::get('/pasien', \App\Http\Livewire\PasienLoket::class)->name('pasien');
=======
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
>>>>>>> a35650fe089b9eeded013ae0f9469ed4217c8243

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
<<<<<<< HEAD
    Route::get('/petugas', \App\Http\Livewire\PetugasLoket::class)->name('petugas');
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
=======
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
>>>>>>> a35650fe089b9eeded013ae0f9469ed4217c8243
    })->name('logout');
});
