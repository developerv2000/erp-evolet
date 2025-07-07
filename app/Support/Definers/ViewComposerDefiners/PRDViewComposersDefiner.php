<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\InvoicePaymentType;
use App\Models\Manufacturer;
use App\Models\MarketingAuthorizationHolder;
use App\Models\Order;
use App\Models\Process;
use App\Models\User;
use Illuminate\Support\Facades\View;

class PRDViewComposersDefiner
{
    public static function defineAll()
    {
        self::defineOrdersComposers();
        self::defineOrderProductsComposers();
        self::defineInvoicesComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function defineOrdersComposers()
    {
        View::composer('PRD.orders.partials.filter', function ($view) {
            $view->with([
                'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'orderNames' => Order::onlyWithName()->orderByName()->pluck('name'),
            ]);
        });
    }

    private static function defineOrderProductsComposers()
    {
        View::composer('PRD.order-products.partials.filter', function ($view) {
            $view->with([
                'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'bdmUsers' => User::getCMDBDMsMinifed(),
                'statusOptions' => Order::getFilterStatusOptions(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'enTrademarks' => Process::pluckAllEnTrademarks(),
                'ruTrademarks' => Process::pluckAllRuTrademarks(),
                'orderNames' => Order::onlyWithName()->orderByName()->pluck('name'),
            ]);
        });
    }

    private static function defineInvoicesComposers()
    {
        View::composer('PRD.invoices.partials.filter', function ($view) {
            $view->with([
                'paymentTypes' => InvoicePaymentType::orderBy('id')->get(),
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
