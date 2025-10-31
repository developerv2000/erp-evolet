<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Manufacturer;
use App\Models\Order;
use App\Models\PayerCompany;
use App\Models\Process;
use Illuminate\Support\Facades\View;

class WarehouseViewComposersDefiner
{
    public static function defineAll()
    {
        self::defineProductsComposers();
        self::defineProductBatchesComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function defineProductsComposers()
    {
        View::composer('warehouse.products.partials.filter', function ($view) {
            $view->with([
                'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'orderNames' => Order::onlyWithName()->orderByName()->pluck('name'),
                'enTrademarks' => Process::pluckAllEnTrademarks(),
                'ruTrademarks' => Process::pluckAllRuTrademarks(),
            ]);
        });

        View::composer('warehouse.products.partials.edit-form', function ($view) {
            $view->with([
                'payerCompanies' => PayerCompany::all(),
            ]);
        });
    }

    private static function defineProductBatchesComposers()
    {
        View::composer('warehouse.product-batches.partials.filter', function ($view) {
            $view->with([
                'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'orderNames' => Order::onlyWithName()->orderByName()->pluck('name'),
                'enTrademarks' => Process::pluckAllEnTrademarks(),
                'ruTrademarks' => Process::pluckAllRuTrademarks(),
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */
}
