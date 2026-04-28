<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Anggota;
use Illuminate\Support\Str;

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
        // Route model binding for Anggota
        $this->app['router']->model('anggota', Anggota::class);
        
        // Increase upload limits
        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');
        ini_set('memory_limit', '256M');
    }
}
