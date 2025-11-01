<?php

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('auth/google/callback', function () {
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
});

Route::middleware('auth')->group(function () {
    Route::get('/petugas', \App\Http\Livewire\PetugasLoket::class);
});
