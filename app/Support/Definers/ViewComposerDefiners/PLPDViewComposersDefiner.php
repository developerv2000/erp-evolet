<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Inn;
use App\Models\Manufacturer;
use App\Models\MarketingAuthorizationHolder;
use App\Models\Order;
use App\Models\Process;
use App\Models\ProductForm;
use App\Models\User;
use Illuminate\Support\Facades\View;

class PLPDViewComposersDefiner
{
    public static function defineAll()
    {
        self::defineReadyForOrderProcessesComposers();
        self::defineOrdersComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function defineReadyForOrderProcessesComposers()
    {
        View::composer('PLPD.ready-for-order-processes.partials.filter', function ($view) {
            $view->with([
                'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'inns' => Inn::orderByName()->get(),
                'productForms' => ProductForm::getMinifiedRecordsWithName(),
                'bdmUsers' => User::getCMDBDMsMinifed(),
                'enTrademarks' => Process::pluckAllEnTrademarks(),
                'ruTrademarks' => Process::pluckAllRuTrademarks(),
            ]);
        });
    }

    private static function defineOrdersComposers()
    {
        View::composer('PLPD.orders.partials.filter', function ($view) {
            $view->with(array_merge(self::getDefaultOrdersShareData(), [
                'orders' => Order::onlyWithName()->orderByName()->get(),
                'enTrademarks' => Process::pluckAllEnTrademarks(),
                'ruTrademarks' => Process::pluckAllRuTrademarks(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'bdmUsers' => User::getCMDBDMsMinifed(),
                'currencies' => Currency::orderByName()->get(),
                'statusOptions' => Order::getFilterStatusOptions(),
            ]));
        });

        View::composer('PLPD.orders.partials.create-form', function ($view) {
            $view->with(self::getDefaultOrdersShareData());
        });

        View::composer('PLPD.orders.partials.edit-form', function ($view) {
            $view->with(array_merge(self::getDefaultOrdersShareData(), [
                'currencies' => Currency::orderByName()->get(),
                'defaultSelectedCurrencyID' => Currency::getDefaultIdValueForMADProcesses(),
            ]));
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */

    private static function getDefaultOrdersShareData()
    {
        return [
            'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
            'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
        ];
    }
}
