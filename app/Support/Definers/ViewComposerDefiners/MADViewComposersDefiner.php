<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Inn;
use App\Models\Manufacturer;
use App\Models\ManufacturerBlacklist;
use App\Models\ManufacturerCategory;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\ProductForm;
use App\Models\ProductShelfLife;
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
        self::defineProductComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definer functions
    |--------------------------------------------------------------------------
    */

    private static function defineManufacturerComposers()
    {
        $defaultShareData = self::getDefaultManufacturersShareData();

        self::defineViewComposer('manufacturers.partials.create-form', array_merge($defaultShareData, [
            'statusOptions' => Manufacturer::getStatusOptions(),
            'defaultSelectedZoneIDs' => Zone::getRelatedDefaultSelectedIDValues(),
        ]));

        self::defineViewComposer('manufacturers.partials.edit-form', array_merge($defaultShareData, [
            'statusOptions' => Manufacturer::getStatusOptions(),
        ]));

        self::defineViewComposer('manufacturers.partials.filter', array_merge($defaultShareData, [
            'regions' => Country::getRegionOptions(),
        ]));
    }

    private static function defineProductComposers()
    {
        $defaultShareData = self::getDefaultProductsShareData();

        self::defineViewComposer('products.partials.create-form', array_merge($defaultShareData, [
            'defaultSelectedClassID' => ProductClass::getDefaultSelectedIDValue(),
            'defaultSelectedZoneIDs' => Zone::getRelatedDefaultSelectedIDValues(),
        ]));

        self::defineViewComposer('products.partials.edit-form', $defaultShareData);

        self::defineViewComposer('products.partials.filter', array_merge($defaultShareData, [
            'brands' => Product::getAllUniqueBrands(),
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
            'productClasses' => ProductClass::orderByName()->get(),
            'blacklists' => ManufacturerBlacklist::orderByName()->get(),
            'booleanOptions' => GeneralHelper::getBooleanOptionsArray(),
        ];
    }

    private static function getDefaultProductsShareData()
    {
        return [
            'manufacturers' => Manufacturer::getMinifiedRecordsWithName(),
            'analystUsers' => User::getMADAnalystsMinified(),
            'bdmUsers' => User::getBDMsMinifed(),
            'productClasses' => ProductClass::orderByName()->get(),
            'productForms' => ProductForm::orderByName()->get(),
            'shelfLifes' => ProductShelfLife::all(),
            'zones' => Zone::orderByName()->get(),
            'inns' => Inn::orderByName()->get(),
            'countriesOrderedByName' => Country::orderByName()->get(),
            'manufacturerCategories' => ManufacturerCategory::orderByName()->get(),
            'booleanOptions' => GeneralHelper::getBooleanOptionsArray(),
        ];
    }
}
