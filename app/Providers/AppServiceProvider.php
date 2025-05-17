<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Set default timezone ke Asia/Jakarta (WIB) untuk seluruh aplikasi, termasuk database
        date_default_timezone_set('Asia/Jakarta');
        // Register observers for history logging
        $models = [
            \App\Models\Barang::class,
            \App\Models\Tempat::class,
            \App\Models\TransaksiMasuk::class,
            \App\Models\TransaksiKeluar::class,
            // \App\Models\Opname::class, // Opname tidak di-observe, pencatatan manual di controller
        ];
        foreach ($models as $model) {
            $model::observe(\App\Observers\HistoryObserver::class);
        }
    }
}
