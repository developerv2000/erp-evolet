<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Inn;
use App\Models\Manufacturer;
use App\Models\MarketingAuthorizationHolder;
use App\Models\Process;
use App\Models\ProductForm;
use App\Models\User;
use Illuminate\Support\Facades\View;

class PLPDViewComposersDefiner
{
    public static function defineAll()
    {
        self::defineReadyForOrderProcessesComposers();
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
                'manufacturers' => Manufacturer::getRecordsWithProcessesReadyForOrder(),
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

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */
}
