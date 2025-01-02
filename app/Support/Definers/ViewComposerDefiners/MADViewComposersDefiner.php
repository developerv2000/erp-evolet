<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Manufacturer;
use App\Models\ManufacturerBlacklist;
use App\Models\ManufacturerCategory;
use App\Models\User;
use App\Models\Zone;
use App\Support\Helpers\GeneralHelper;
use App\Support\Traits\Misc\DefinesViewComposer;

class MADViewComposersDefiner
{
    use DefinesViewComposer;

    public static function defineAll()
    {
        self::defineManufacturerComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definer functions
    |--------------------------------------------------------------------------
    */

    private static function defineManufacturerComposers()
    {
        $defaultShareData = self::getDefaultManufacturersShareData();
        self::defineViewComposer(['manufacturers.partials.filter'], array_merge($defaultShareData, [
            'regions' => Country::getRegionOptions(),
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | Default shared datas
    |--------------------------------------------------------------------------
    */

    private static function getDefaultManufacturersShareData()
    {
        return [
            'analystUsers' => User::getMADAnalystsMinified(),
            'bdmUsers' => User::getBDMsMinifed(),
            'countriesOrderedByName' => Country::orderByName()->get(),
            'countriesOrderedByUsageCount' => Country::orderByUsageCount()->get(),
            'manufacturers' => Manufacturer::getMinifiedRecordsWithName(),
            'categories' => ManufacturerCategory::orderByName()->get(),
            'zones' => Zone::orderByName()->get(),
            // 'productClasses' => ProductClass::getAll(),
            'blacklists' => ManufacturerBlacklist::orderByName()->get(),
            'booleanOptions' => GeneralHelper::getBooleanOptionsArray(),
        ];
    }
}
