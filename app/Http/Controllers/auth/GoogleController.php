<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Coba dengan retry logic untuk menangani connection issues
            $maxRetries = 5;
            $user = null;
            $lastException = null;
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    // Disconnect dulu untuk memastikan connection baru
                    if ($attempt > 1) {
                        try {
                            \DB::disconnect('pgsql');
                        } catch (\Exception $e) {
                            // Ignore disconnect errors
                        }
                    }
                    
                    // Tunggu sebelum retry (exponential backoff)
                    if ($attempt > 1) {
                        $delay = min(1000000 * $attempt, 3000000); // 1s, 2s, 3s max
                        usleep($delay);
                    }
                    
                    // Reconnect database
                    \DB::reconnect('pgsql');
                    
                    // Test connection dulu
                    \DB::connection('pgsql')->getPdo();
                    
                    // Cari atau buat user berdasarkan email
                    $user = User::firstOrCreate(
                        ['email' => $googleUser->getEmail()],
                        [
                            'name' => $googleUser->getName(),
                            'password' => bcrypt(Str::random(16)),
                            'email_verified_at' => now(),
                        ]
                    );
                    
                    // Jika berhasil, keluar dari loop
                    break;
                } catch (\Illuminate\Database\QueryException $dbException) {
                    $lastException = $dbException;
                    
                    // Jika error terkait connection dan masih ada retry
                    if ($attempt < $maxRetries) {
                        $errorMessage = $dbException->getMessage();
                        
                        if (str_contains($errorMessage, 'server closed the connection') ||
                            str_contains($errorMessage, 'connection unexpectedly') ||
                            str_contains($errorMessage, 'Connection refused') ||
                            str_contains($errorMessage, 'could not connect')) {
                            // Continue to retry
                            continue;
                        }
                    }
                    
                    // Jika sudah semua retry gagal atau error lain, throw exception
                    throw $dbException;
                } catch (\PDOException $pdoException) {
                    $lastException = $pdoException;
                    
                    // Jika error terkait connection dan masih ada retry
                    if ($attempt < $maxRetries) {
                        $errorMessage = $pdoException->getMessage();
                        
                        if (str_contains($errorMessage, 'server closed the connection') ||
                            str_contains($errorMessage, 'connection unexpectedly') ||
                            str_contains($errorMessage, 'Connection refused')) {
                            // Continue to retry
                            continue;
                        }
                    }
                    
                    // Convert PDOException to QueryException
                    throw new \Illuminate\Database\QueryException(
                        'pgsql',
                        '',
                        [],
                        $pdoException
                    );
                }
            }
            
            if (!$user) {
                $errorMsg = $lastException ? $lastException->getMessage() : 'Unknown error';
                \Log::error('Failed to create/find user after ' . $maxRetries . ' attempts: ' . $errorMsg);
                throw new \Exception('Gagal membuat atau menemukan user setelah beberapa percobaan.');
            }

            // Login user
            Auth::login($user, true);

            // Redirect ke halaman setelah login
            return redirect('/petugas')
                ->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error during Google login: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')
                ->with('error', 'Terjadi masalah dengan koneksi database. Silakan coba lagi dalam beberapa saat.');
        } catch (\Exception $e) {
            \Log::error('Error during Google login: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')
                ->with('error', 'Terjadi kesalahan saat login dengan Google: ' . substr($e->getMessage(), 0, 150));
        }
    }
}
