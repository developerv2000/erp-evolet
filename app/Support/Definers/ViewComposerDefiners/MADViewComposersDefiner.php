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
use App\Support\SmartFilters\MAD\MADManufacturersSmartFilter;
use App\Support\SmartFilters\MAD\MADProcessesSmartFilter;
use App\Support\SmartFilters\MAD\MADProductsSmartFilter;
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
        View::composer('MAD.manufacturers.partials.create-form', function ($view) {
            $view->with(array_merge(self::getDefaultManufacturersShareData(), [
                'defaultSelectedZoneIDs' => Zone::getRelatedDefaultSelectedIDValues(),
            ]));
        });

        View::composer('MAD.manufacturers.partials.edit-form', function ($view) {
            $view->with(self::getDefaultManufacturersShareData());
        });

        View::composer('MAD.manufacturers.partials.filter', function ($view) {
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
                'smartFilterDependencies' => MADManufacturersSmartFilter::getAllDependencies(),
            ]);
        });
    }

    private static function defineProductComposers()
    {
        View::composer('MAD.products.partials.create-form', function ($view) {
            $view->with(array_merge(self::getDefaultProductsShareData(), [
                'defaultSelectedClassID' => ProductClass::getDefaultSelectedIDValue(),
                'defaultSelectedShelfLifeID' => ProductShelfLife::getDefaultSelectedIDValue(),
                'defaultSelectedZoneIDs' => Zone::getRelatedDefaultSelectedIDValues(),
            ]));
        });

        View::composer('MAD.products.partials.edit-form', function ($view) {
            $view->with(self::getDefaultProductsShareData());
        });

        View::composer('MAD.products.partials.filter', function ($view) {
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
                'smartFilterDependencies' => MADProductsSmartFilter::getAllDependencies(),
            ]);
        });
    }

    private static function defineProcessComposers()
    {
        View::composer([
            'MAD.processes.partials.create-form',
            'MAD.processes.partials.edit-form',
            'MAD.processes.partials.duplicate-form',
        ], function ($view) {
            $view->with([
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'responsiblePeople' => ProcessResponsiblePerson::orderByName()->get(),
                'defaultSelectedStatusIDs' => ProcessStatus::getDefaultSelectedIDValue(),
            ]);
        });

        View::composer('MAD.processes.partials.edit-product-form-block', function ($view) {
            $view->with([
                'productForms' => ProductForm::getMinifiedRecordsWithName(),
                'shelfLifes' => ProductShelfLife::all(),
                'productClasses' => ProductClass::orderByName()->get(),
            ]);
        });

        View::composer([
            'MAD.processes.partials.create-form-stage-inputs',
            'MAD.processes.partials.edit-form-stage-inputs',
            'MAD.processes.partials.duplicate-form-stage-inputs',
        ], function ($view) {
            $view->with([
                'countriesOrderedByName' => Country::orderByName()->get(),
                'currencies' => Currency::orderByName()->get(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'defaultSelectedMAHID' => MarketingAuthorizationHolder::getDefaultSelectedIDValue(),
                'defaultSelectedCurrencyID' => Currency::getDefaultIdValueForMADProcesses(),
            ]);
        });

        View::composer('MAD.processes.partials.filter', function ($view) {
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
                'smartFilterDependencies' => MADProcessesSmartFilter::getAllDependencies(),
            ]);
        });
    }

    private static function defineProductSearchComposers()
    {
        View::composer('MAD.product-searches.partials.create-form', function ($view) {
            $view->with(array_merge(self::getDefaultProductSearchesShareData(), [
                'defaultSelectedStatusID' => ProductSearchStatus::getDefaultSelectedIDValue(),
                'defaultSelectedPriorityID' => ProductSearchPriority::getDefaultSelectedIDValue(),
            ]));
        });

        View::composer([
            'MAD.product-searches.partials.edit-form',
            'MAD.product-searches.partials.filter'
        ], function ($view) {
            $view->with(self::getDefaultProductSearchesShareData());
        });
    }

    private static function defineMeetingComposers()
    {
        View::composer([
            'MAD.meetings.partials.filter',
            'MAD.meetings.partials.create-form',
            'MAD.meetings.partials.edit-form'
        ], function ($view) {
            $view->with(self::getDefaultMeetingsShareData());
        });
    }

    private static function defineKPIComposers()
    {
        View::composer('MAD.kpi.partials.filter', function ($view) {
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
        View::composer('MAD.asp.partials.create-form', function ($view) {
            $view->with([
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
            ]);
        });

        View::composer('MAD.asp.partials.show-page-filter', function ($view) {
            $view->with([
                'regions' => Country::getRegionOptions(),
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'displayOptions' => MadAsp::getFilterDisplayOptions(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
            ]);
        });

        // Countries
        View::composer('MAD.asp.countries.partials.create-form', function ($view) {
            $view->with([
                'countriesOrderedByProcessesCount' => Country::orderByProcessesCount()->get(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
            ]);
        });

        // MAHs
        View::composer('MAD.asp.mahs.partials.table', function ($view) {
            $view->with([
                'months' => GeneralHelper::collectCalendarMonths(),
            ]);
        });

        View::composer([
            'MAD.asp.mahs.partials.create-form',
            'MAD.asp.mahs.partials.edit-form',
        ], function ($view) {
            $view->with([
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'months' => GeneralHelper::collectCalendarMonths(),
            ]);
        });
    }

    private static function defineDHComposers()
    {
        View::composer('MAD.decision-hub.partials.filter', function ($view) {
            $view->with([
                'countriesOrderedByName' => Country::orderByName()->get(),
                'analystUsers' => User::getMADAnalystsMinified(),
                'bdmUsers' => User::getBDMsMinifed(),
                'MAHs' => MarketingAuthorizationHolder::orderByName()->get(),
                'regions' => Country::getRegionOptions(),
                'generalStatusNamesForAnalysts' => ProcessGeneralStatus::getUniqueNamesForAnalysts(),
                'smartFilterDependencies' => MADProcessesSmartFilter::getAllDependencies(), // Exactly same as VPS smart filter
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
