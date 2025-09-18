<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\PayerCompany;
use Illuminate\Support\Facades\View;

class WarehouseViewComposersDefiner
{
    public static function defineAll()
    {
        self::defineProductsComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function defineProductsComposers()
    {
        View::composer('warehouse.products.partials.edit-form', function ($view) {
            $view->with([
                'payerCompanies' => PayerCompany::all(),
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */
}
