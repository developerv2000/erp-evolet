<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Manufacturer;
use App\Models\MarketingAuthorizationHolder;
use App\Models\Order;
use App\Models\Process;
use Illuminate\Support\Facades\View;

class DDViewComposersDefiner
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
        View::composer('DD.order-products.partials.filter', function ($view) {
            $view->with([
                'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'enTrademarks' => Process::pluckAllEnTrademarks(),
                'orderNames' => Order::onlyWithName()->orderByName()->pluck('name'),
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */
}
