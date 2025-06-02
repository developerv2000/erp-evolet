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

class CMDViewComposersDefiner
{
    public static function defineAll()
    {
        self::defineOrdersComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function defineOrdersComposers()
    {
        View::composer('CMD.orders.partials.filter', function ($view) {
            $view->with([
                'orderNames' => Order::onlyWithName()->orderByName()->get(),
                'manufacturers' => Manufacturer::getMinifiedRecordsWithProcessesReadyForOrder(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'enTrademarks' => Process::pluckAllEnTrademarks(),
                'ruTrademarks' => Process::pluckAllRuTrademarks(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'bdmUsers' => User::getCMDBDMsMinifed(),
                'currencies' => Currency::orderByName()->get(),
            ]);
        });

        View::composer('CMD.orders.partials.edit-form', function ($view) {
            $view->with([
                'currencies' => Currency::orderByName()->get(),
                'defaultSelectedCurrencyID' => Currency::getDefaultIdValueForMADProcesses(),
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */
}
