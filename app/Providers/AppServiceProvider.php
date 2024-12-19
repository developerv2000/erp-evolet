<?php

namespace App\Providers;

use App\Support\Definers\GateDefiners\GlobalGatesDefiner;
use App\Support\Definers\GateDefiners\MADGatesDefiner;
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
        GlobalGatesDefiner::defineAll();
        MADGatesDefiner::defineAll();
    }
}
