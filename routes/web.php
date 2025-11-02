<?php

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

Route::middleware('auth')->group(function () {
    Route::get('/petugas', \App\Http\Livewire\PetugasLoket::class)->name('petugas');
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});
