<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Pereaksi;            
use App\Observers\PereaksiObserver; 

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
        // if(config('app.env') === 'local') {
        //     URL::forceScheme('https');
        // }

        Pereaksi::observe(PereaksiObserver::class);
    }
}
