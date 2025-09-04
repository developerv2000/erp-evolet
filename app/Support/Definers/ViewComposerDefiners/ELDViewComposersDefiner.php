<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Currency;
use App\Models\Process;
use App\Models\ShipmentDestination;
use App\Models\ShipmentType;
use Illuminate\Support\Facades\View;

class ELDViewComposersDefiner
{
    public static function defineAll()
    {
        self::defineOrderProductsComposers();
        self::defineInvoicesComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function defineOrderProductsComposers()
    {
        View::composer('ELD.order-products.partials.edit-form', function ($view) {
            $view->with([
                'shipmentTypes' => ShipmentType::all(),
                'shipmentDestinations' => ShipmentDestination::all(),
                'currencies' => Currency::all(),
            ]);
        });
    }

    private static function defineInvoicesComposers()
    {
        View::composer('ELD.invoices.partials.filter', function ($view) {
            $view->with([

            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */
}
