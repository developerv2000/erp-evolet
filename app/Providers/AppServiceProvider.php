<?php

namespace App\Providers;

use App\Support\Definers\GateDefiners\CMDGatesDefiner;
use App\Support\Definers\GateDefiners\DDGatesDefiner;
use App\Support\Definers\GateDefiners\GlobalGatesDefiner;
use App\Support\Definers\GateDefiners\MADGatesDefiner;
use App\Support\Definers\GateDefiners\MSDGatesDefiner;
use App\Support\Definers\GateDefiners\PLPDGatesDefiner;
use App\Support\Definers\GateDefiners\PRDGatesDefiner;
use App\Support\Definers\GateDefiners\SharedGatesDefiner;
use App\Support\Definers\ViewComposerDefiners\CMDViewComposersDefiner;
use App\Support\Definers\ViewComposerDefiners\DDViewComposersDefiner;
use App\Support\Definers\ViewComposerDefiners\GlobalViewComposersDefiner;
use App\Support\Definers\ViewComposerDefiners\MADViewComposersDefiner;
use App\Support\Definers\ViewComposerDefiners\MGMTViewComposersDefiner;
use App\Support\Definers\ViewComposerDefiners\MSDViewComposersDefiner;
use App\Support\Definers\ViewComposerDefiners\PLPDViewComposersDefiner;
use App\Support\Definers\ViewComposerDefiners\PRDViewComposersDefiner;
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
        PLPDGatesDefiner::defineAll();
        CMDGatesDefiner::defineAll();
        DDGatesDefiner::defineAll();
        PRDGatesDefiner::defineAll();
        MSDGatesDefiner::defineAll();
        SharedGatesDefiner::defineAll();

        // View composer definers
        GlobalViewComposersDefiner::defineAll();
        MGMTViewComposersDefiner::defineAll();
        MADViewComposersDefiner::defineAll();
        PLPDViewComposersDefiner::defineAll();
        CMDViewComposersDefiner::defineAll();
        DDViewComposersDefiner::defineAll();
        PRDViewComposersDefiner::defineAll();
        MSDViewComposersDefiner::defineAll();
    }
}
