<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\URL;

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
        // Configurar Vite para buscar el manifest en .vite/manifest.json
        Vite::useManifestFilename('.vite/manifest.json');
        
        // Forzar HTTPS en producción para evitar Mixed Content
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
