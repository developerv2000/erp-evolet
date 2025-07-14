<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Manufacturer;
use App\Models\Process;
use Illuminate\Support\Facades\View;

class MSDViewComposersDefiner
{
    public static function defineAll()
    {
        self::defineOrderProductsComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function defineOrderProductsComposers()
    {
        View::composer('MSD.order-products.serialized-by-manufacturer.partials.filter', function ($view) {
            $view->with([
                'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'enTrademarks' => Process::pluckAllEnTrademarks(),
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */
}
