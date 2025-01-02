<?php

namespace App\Providers;

use App\Support\Definers\GateDefiners\GlobalGatesDefiner;
use App\Support\Definers\GateDefiners\MADGatesDefiner;
use App\Support\Definers\ViewComposerDefiners\GlobalViewComposersDefiner;
use App\Support\Definers\ViewComposerDefiners\MADViewComposersDefiner;
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
        // Gate definers
        GlobalGatesDefiner::defineAll();
        MADGatesDefiner::defineAll();

        // View composer definers
        GlobalViewComposersDefiner::defineAll();
        MADViewComposersDefiner::defineAll();
    }
}
