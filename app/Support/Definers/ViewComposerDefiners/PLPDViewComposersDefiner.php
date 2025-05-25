<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Support\Helpers\GeneralHelper;
use Illuminate\Support\Facades\View;

class PLPDViewComposersDefiner
{
    public static function defineAll()
    {
        // self::defineManufacturerComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    // private static function defineProductSearchComposers()
    // {
    //     View::composer('MAD.product-searches.partials.create-form', function ($view) {
    //         $view->with(array_merge(self::getDefaultProductSearchesShareData(), [
    //             'defaultSelectedStatusID' => ProductSearchStatus::getDefaultSelectedIDValue(),
    //             'defaultSelectedPriorityID' => ProductSearchPriority::getDefaultSelectedIDValue(),
    //         ]));
    //     });

    //     View::composer([
    //         'MAD.product-searches.partials.edit-form',
    //         'MAD.product-searches.partials.filter'
    //     ], function ($view) {
    //         $view->with(self::getDefaultProductSearchesShareData());
    //     });
    // }

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */

    // private static function getDefaultMeetingsShareData()
    // {
    //     return [
    //         'manufacturers' => Manufacturer::getMinifiedRecordsWithName(),
    //         'analystUsers' => User::getMADAnalystsMinified(),
    //         'bdmUsers' => User::getCMDBDMsMinifed(),
    //         'countriesOrderedByName' => Country::orderByName()->get(),
    //     ];
    // }
}
