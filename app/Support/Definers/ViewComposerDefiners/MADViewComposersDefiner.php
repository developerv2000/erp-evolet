<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Inn;
use App\Models\MadAsp;
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
use App\Support\SmartFilters\MadManufacturersSmartFilter;
use App\Support\SmartFilters\MadProcessesSmartFilter;
use App\Support\SmartFilters\MadProductsSmartFilter;
use Illuminate\Support\Facades\View;

class MADViewComposersDefiner
{
    public static function defineAll()
    {
        self::defineManufacturerComposers();
        self::defineProductComposers();
        self::defineProcessComposers();
        self::defineProductSearchComposers();
        self::defineKPIComposers();
        self::defineASPComposers();
        self::defineMeetingComposers();
        self::defineDHComposers();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function defineManufacturerComposers()
    {
        View::composer('manufacturers.partials.create-form', function ($view) {
            $view->with(array_merge(self::getDefaultManufacturersShareData(), [
                'defaultSelectedZoneIDs' => Zone::getRelatedDefaultSelectedIDValues(),
            ]));
        });

        View::composer('manufacturers.partials.edit-form', function ($view) {
            $view->with(self::getDefaultManufacturersShareData());
        });

        View::composer('manufacturers.partials.filter', function ($view) {
            $view->with([
                'bdmUsers' => User::getBDMsMinifed(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'categories' => ManufacturerCategory::orderByName()->get(),
                'zones' => Zone::orderByName()->get(),
                'productClasses' => ProductClass::orderByName()->get(),
                'blacklists' => ManufacturerBlacklist::orderByName()->get(),
                'booleanOptions' => GeneralHelper::getBooleanOptionsArray(),
                'statusOptions' => Manufacturer::getStatusOptions(),
                'regions' => Country::getRegionOptions(),
                'smartFilterDependencies' => MadManufacturersSmartFilter::getAllDependencies(),
            ]);
        });
    }

    private static function defineProductComposers()
    {
        View::composer('products.partials.create-form', function ($view) {
            $view->with(array_merge(self::getDefaultProductsShareData(), [
                'defaultSelectedClassID' => ProductClass::getDefaultSelectedIDValue(),
                'defaultSelectedShelfLifeID' => ProductShelfLife::getDefaultSelectedIDValue(),
                'defaultSelectedZoneIDs' => Zone::getRelatedDefaultSelectedIDValues(),
            ]));
        });

        View::composer('products.partials.edit-form', function ($view) {
            $view->with(self::getDefaultProductsShareData());
        });

        View::composer('products.partials.filter', function ($view) {
            $view->with([
                'analystUsers' => User::getMADAnalystsMinified(),
                'bdmUsers' => User::getBDMsMinifed(),
                'productClasses' => ProductClass::orderByName()->get(),
                'shelfLifes' => ProductShelfLife::all(),
                'zones' => Zone::orderByName()->get(),
                'countriesOrderedByName' => Country::orderByName()->get(),
                'manufacturerCategories' => ManufacturerCategory::orderByName()->get(),
                'booleanOptions' => GeneralHelper::getBooleanOptionsArray(),
                'brands' => Product::getAllUniqueBrands(),
                'smartFilterDependencies' => MadProductsSmartFilter::getAllDependencies(),
            ]);
        });
    }

    private static function defineProcessComposers()
    {
        View::composer([
            'processes.partials.create-form',
            'processes.partials.edit-form',
            'processes.partials.duplicate-form',
        ], function ($view) {
            $view->with([
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'responsiblePeople' => ProcessResponsiblePerson::orderByName()->get(),
                'defaultSelectedStatusIDs' => ProcessStatus::getDefaultSelectedIDValue(),
            ]);
        });

        View::composer('processes.partials.edit-product-form-block', function ($view) {
            $view->with([
                'productForms' => ProductForm::getMinifiedRecordsWithName(),
                'shelfLifes' => ProductShelfLife::all(),
                'productClasses' => ProductClass::orderByName()->get(),
            ]);
        });

        View::composer([
            'processes.partials.create-form-stage-inputs',
            'processes.partials.edit-form-stage-inputs',
            'processes.partials.duplicate-form-stage-inputs',
        ], function ($view) {
            $view->with([
                'countriesOrderedByName' => Country::orderByName()->get(),
                'currencies' => Currency::orderByName()->get(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'defaultSelectedMAHID' => MarketingAuthorizationHolder::getDefaultSelectedIDValue(),
                'defaultSelectedCurrencyID' => Currency::getDefaultIdValueForMADProcesses(),
            ]);
        });

        View::composer('processes.partials.filter', function ($view) {
            $view->with([
                'countriesOrderedByName' => Country::orderByName()->get(),
                'analystUsers' => User::getMADAnalystsMinified(),
                'bdmUsers' => User::getBDMsMinifed(),
                'responsiblePeople' => ProcessResponsiblePerson::orderByName()->get(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'productClasses' => ProductClass::orderByName()->get(),
                'manufacturerCategories' => ManufacturerCategory::orderByName()->get(),
                'generalStatuses' => ProcessGeneralStatus::all(),
                'generalStatusNamesForAnalysts' => ProcessGeneralStatus::getUniqueNamesForAnalysts(),
                'regions' => Country::getRegionOptions(),
                'brands' => Product::getAllUniqueBrands(),
                'smartFilterDependencies' => MadProcessesSmartFilter::getAllDependencies(),
            ]);
        });
    }

    private static function defineProductSearchComposers()
    {
        View::composer('product-searches.partials.create-form', function ($view) {
            $view->with(array_merge(self::getDefaultProductSearchesShareData(), [
                'defaultSelectedStatusID' => ProductSearchStatus::getDefaultSelectedIDValue(),
                'defaultSelectedPriorityID' => ProductSearchPriority::getDefaultSelectedIDValue(),
            ]));
        });

        View::composer([
            'product-searches.partials.edit-form',
            'product-searches.partials.filter'
        ], function ($view) {
            $view->with(self::getDefaultProductSearchesShareData());
        });
    }

    private static function defineMeetingComposers()
    {
        View::composer([
            'meetings.partials.filter',
            'meetings.partials.create-form',
            'meetings.partials.edit-form'
        ], function ($view) {
            $view->with(self::getDefaultMeetingsShareData());
        });
    }

    private static function defineKPIComposers()
    {
        View::composer('mad-kpi.partials.filter', function ($view) {
            $view->with([
                'analystUsers' => User::getMADAnalystsMinified(),
                'bdmUsers' => User::getBDMsMinifed(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'regions' => Country::getRegionOptions(),
                'months' => GeneralHelper::collectCalendarMonths(),
            ]);
        });
    }

    private static function defineASPComposers()
    {
        View::composer('mad-asp.partials.create-form', function ($view) {
            $view->with([
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
            ]);
        });

        View::composer('mad-asp.partials.show-page-filter', function ($view) {
            $view->with([
                'regions' => Country::getRegionOptions(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'displayOptions' => MadAsp::getFilterDisplayOptions(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
            ]);
        });

        // Countries
        View::composer('mad-asp.countries.partials.create-form', function ($view) {
            $view->with([
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
            ]);
        });

        // MAHs
        View::composer('mad-asp.mahs.partials.table', function ($view) {
            $view->with([
                'months' => GeneralHelper::collectCalendarMonths(),
            ]);
        });

        View::composer([
            'mad-asp.mahs.partials.create-form',
            'mad-asp.mahs.partials.edit-form',
        ], function ($view) {
            $view->with([
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'months' => GeneralHelper::collectCalendarMonths(),
            ]);
        });
    }

    private static function defineDHComposers()
    {
        View::composer('decision-hub.partials.filter', function ($view) {
            $view->with([
                'countriesOrderedByName' => Country::orderByName()->get(),
                'analystUsers' => User::getMADAnalystsMinified(),
                'bdmUsers' => User::getBDMsMinifed(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'regions' => Country::getRegionOptions(),
                'generalStatusNamesForAnalysts' => ProcessGeneralStatus::getUniqueNamesForAnalysts(),
                'smartFilterDependencies' => MadProcessesSmartFilter::getAllDependencies(), // Exactly same as VPS smart filter
            ]);
        });
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
            'statusOptions' => Manufacturer::getStatusOptions(),
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

    private static function getDefaultMeetingsShareData()
    {
        return [
            'manufacturers' => Manufacturer::getMinifiedRecordsWithName(),
            'analystUsers' => User::getMADAnalystsMinified(),
            'bdmUsers' => User::getBDMsMinifed(),
            'countriesOrderedByName' => Country::orderByName()->get(),
        ];
    }
}
