<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Currency;
use App\Models\InvoicePaymentType;
use App\Models\Manufacturer;
use App\Models\MarketingAuthorizationHolder;
use App\Models\Order;
use App\Models\Process;
use App\Models\User;
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
        View::composer('ELD.invoices.partials.filter', function ($view) {
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
