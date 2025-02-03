<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Inn;
use App\Models\Manufacturer;
use App\Models\ManufacturerBlacklist;
use App\Models\ManufacturerCategory;
use App\Models\MarketingAuthorizationHolder;
use App\Models\PortfolioManager;
use App\Models\ProcessGeneralStatus;
use App\Models\ProcessResponsiblePerson;
use App\Models\ProcessStatus;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\ProductForm;
use App\Models\ProductSearchPriority;
use App\Models\ProductSearchStatus;
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
        self::defineProcessComposers();
        self::defineProductSearchComposers();
        self::defineKPIComposers();
        self::defineASPComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
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
            'defaultSelectedShelfLifeID' => ProductShelfLife::getDefaultSelectedIDValue(),
            'defaultSelectedZoneIDs' => Zone::getRelatedDefaultSelectedIDValues(),
        ]));

        self::defineViewComposer('products.partials.edit-form', $defaultShareData);

        self::defineViewComposer('products.partials.filter', array_merge($defaultShareData, [
            'brands' => Product::getAllUniqueBrands(),
        ]));
    }

    private static function defineProcessComposers()
    {
        self::defineViewComposer([
            'processes.partials.create-form',
            'processes.partials.edit-form',
            'processes.partials.duplicate-form',
        ], [
            'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
            'responsiblePeople' => ProcessResponsiblePerson::orderByName()->get(),
            'defaultSelectedStatusIDs' => ProcessStatus::getDefaultSelectedIDValue(),
        ]);

        self::defineViewComposer('processes.partials.edit-product-form-block', [
            'productForms' => ProductForm::getMinifiedRecordsWithName(),
            'shelfLifes' => ProductShelfLife::all(),
            'productClasses' => ProductClass::orderByName()->get(),
        ]);

        self::defineViewComposer([
            'processes.partials.create-form-stage-inputs',
            'processes.partials.edit-form-stage-inputs',
            'processes.partials.duplicate-form-stage-inputs',
        ], [
            'countriesOrderedByName' => Country::orderByName()->get(),
            'currencies' => Currency::orderByName()->get(),
            'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
            'defaultSelectedMAHID' => MarketingAuthorizationHolder::getDefaultSelectedIDValue(),
        ]);

        self::defineViewComposer('processes.partials.filter', [
            'countriesOrderedByName' => Country::orderByName()->get(),
            'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
            'manufacturers' => Manufacturer::getMinifiedRecordsWithName(),
            'inns' => Inn::orderByName()->get(),
            'productForms' => ProductForm::getMinifiedRecordsWithName(),
            'analystUsers' => User::getMADAnalystsMinified(),
            'bdmUsers' => User::getBDMsMinifed(),
            'responsiblePeople' => ProcessResponsiblePerson::orderByName()->get(),
            'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
            'productClasses' => ProductClass::orderByName()->get(),
            'manufacturerCategories' => ManufacturerCategory::orderByName()->get(),
            'statuses' => ProcessStatus::all(),
            'generalStatuses' => ProcessGeneralStatus::all(),
            'generalStatusNamesForAnalysts' => ProcessGeneralStatus::getUniqueNamesForAnalysts(),
            'regions' => Country::getRegionOptions(),
            'brands' => Product::getAllUniqueBrands(),
        ]);
    }

    private static function defineProductSearchComposers()
    {
        $defaultShareData = self::getDefaultProductSearchesShareData();

        self::defineViewComposer('product-searches.partials.create-form', array_merge($defaultShareData, [
            'defaultSelectedStatusID' => ProductSearchStatus::getDefaultSelectedIDValue(),
            'defaultSelectedPriorityID' => ProductSearchPriority::getDefaultSelectedIDValue(),
        ]));

        self::defineViewComposer([
            'product-searches.partials.edit-form',
            'product-searches.partials.filter'
        ], $defaultShareData);
    }

    private static function defineKPIComposers()
    {
        self::defineViewComposer('mad-kpi.partials.filter',  [
            'analystUsers' => User::getMADAnalystsMinified(),
            'bdmUsers' => User::getBDMsMinifed(),
            'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
            'regions' => Country::getRegionOptions(),
            'months' => GeneralHelper::collectCalendarMonths(),
        ]);
    }

    private static function defineASPComposers()
    {
        self::defineViewComposer('mad-asp.partials.create-form', [
            'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
        ]);

        self::defineViewComposer('mad-asp.countries.partials.create-form', [
            'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
            'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
        ]);
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
            'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
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
            'productForms' => ProductForm::getMinifiedRecordsWithName(),
            'shelfLifes' => ProductShelfLife::all(),
            'zones' => Zone::orderByName()->get(),
            'inns' => Inn::orderByName()->get(),
            'countriesOrderedByName' => Country::orderByName()->get(),
            'manufacturerCategories' => ManufacturerCategory::orderByName()->get(),
            'booleanOptions' => GeneralHelper::getBooleanOptionsArray(),
        ];
    }

    private static function getDefaultProductSearchesShareData()
    {
        return [
            'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
            'booleanOptions' => GeneralHelper::getBooleanOptionsArray(),
            'statuses' => ProductSearchStatus::orderByName()->get(),
            'priorities' => ProductSearchPriority::orderByName()->get(),
            'inns' => Inn::orderByName()->get(),
            'productForms' => ProductForm::getMinifiedRecordsWithName(),
            'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
            'portfolioManagers' => PortfolioManager::orderByName()->get(),
            'analystUsers' => User::getMADAnalystsMinified(),
        ];
    }
}
