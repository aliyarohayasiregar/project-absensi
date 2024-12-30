<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        // Set timezone PHP
        date_default_timezone_set('Asia/Jakarta');
        
        // Set timezone hanya untuk session saat ini
        try {
            DB::statement("SET time_zone = '+07:00'");
        } catch (\Exception $e) {
            \Log::warning('Tidak dapat mengatur timezone MySQL: ' . $e->getMessage());
        }
    }
}
