<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Manufacturer;
use App\Models\Order;
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
        View::composer('ELD.order-products.partials.filter', function ($view) {
            $view->with([
                'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'statusOptions' => Order::getFilterStatusOptions(),
                'enTrademarks' => Process::pluckAllEnTrademarks(),
                'ruTrademarks' => Process::pluckAllRuTrademarks(),
                'orderNames' => Order::onlyWithName()->orderByName()->pluck('name'),
            ]);
        });

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
                'ordersWithName' => Order::onlyWithName()->orderByName()->get(),
                'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */
}
