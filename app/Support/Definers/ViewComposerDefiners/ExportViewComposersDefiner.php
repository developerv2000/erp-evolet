<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\ShipmentType;
use Illuminate\Support\Facades\View;

class ExportViewComposersDefiner
{
    public static function defineAll()
    {
        self::defineAssemblagesComposers();
        self::defineProductsComposers();
        self::defineInvoicesComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function defineAssemblagesComposers()
    {
        View::composer('export.assemblages.partials.filter', function ($view) {
            $view->with([]);
        });

        View::composer('export.assemblages.partials.create-form', function ($view) {
            $view->with([
                'shipmentTypes' => ShipmentType::all(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
            ]);
        });

        View::composer('export.assemblages.partials.edit-form', function ($view) {
            $view->with([]);
        });
    }

    private static function defineProductsComposers() {}
    private static function defineInvoicesComposers() {}

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */
}
