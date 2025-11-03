<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure Livewire component aliases exist for any places that reference
        // fully-qualified component names (e.g. 'app.http.livewire.pasien-loket').
        // This is a safe, non-visual change that prevents ComponentNotFound exceptions
        // when a view or compiled template references the component by dotted name.
        try {
            if (class_exists(\Livewire\Livewire::class)) {
                // Register both kebab and dotted aliases to their classes
                Livewire::component('pasien-loket', \App\Http\Livewire\PasienLoket::class);
                Livewire::component('app.http.livewire.pasien-loket', \App\Http\Livewire\PasienLoket::class);

                Livewire::component('petugas-loket', \App\Http\Livewire\PetugasLoket::class);
                Livewire::component('app.http.livewire.petugas-loket', \App\Http\Livewire\PetugasLoket::class);

                // Components defined under App\Livewire (older/alternate namespace)
                if (class_exists(\App\Livewire\PasienAntrian::class)) {
                    Livewire::component('pasien-antrian', \App\Livewire\PasienAntrian::class);
                    Livewire::component('app.http.livewire.pasien-antrian', \App\Livewire\PasienAntrian::class);
                }
                if (class_exists(\App\Livewire\DisplayAntrian::class)) {
                    Livewire::component('display-antrian', \App\Livewire\DisplayAntrian::class);
                    Livewire::component('app.http.livewire.display-antrian', \App\Livewire\DisplayAntrian::class);
                }
            }
        } catch (\Throwable $e) {
            // Don't break boot if Livewire isn't available; log for debugging.
            Log::warning('Livewire component registration skipped: ' . $e->getMessage());
        }
    }
}
