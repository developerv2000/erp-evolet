<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Manufacturer;
use App\Models\MarketingAuthorizationHolder;
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
            $view->with([
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
            ]);
        });

        View::composer('export.assemblages.partials.create-form', function ($view) {
            $view->with([
                'shipmentTypes' => ShipmentType::all(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
            ]);
        });

        View::composer('export.assemblages.partials.create-form-dynamic-rows-list-item', function ($view) {
            $view->with([
                'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
                'MAHs' => MarketingAuthorizationHolder::all(),
            ]);
        });

        View::composer('export.assemblages.partials.edit-form', function ($view) {
            $view->with([
                'shipmentTypes' => ShipmentType::all(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'currencies' => Currency::all(),
            ]);
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
