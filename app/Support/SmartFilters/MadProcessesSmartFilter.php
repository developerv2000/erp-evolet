<?php

namespace App\Support\SmartFilters;

use App\Models\Country;
use App\Models\Inn;
use App\Models\Manufacturer;
use App\Models\ProductForm;
use Illuminate\Http\Request;

class MadProcessesSmartFilter
{
    public static function getAllDependencies()
    {
        $request = request();

        return [
            'manufacturers' => self::getManufacturers($request),
            'inns' => self::getInns($request),
            'productForms' => self::getForms($request),
            'countriesOrderedByProcessesCount' => self::getCountriesOrderedByProcessesCount($request),
        ];
    }

    private static function getManufacturers(Request $request)
    {
        $query = Manufacturer::query();

        if ($request->filled('inn_id')) {
            $query->whereHas('processes', function ($processesQuery) use ($request) {
                $processesQuery->whereIn('inn_id', $request->input('inn_id'));
            });
        }

        if ($request->filled('form_id')) {
            $query->whereHas('processes', function ($processesQuery) use ($request) {
                $processesQuery->whereIn('form_id', $request->input('form_id'));
            });
        }

        if ($request->filled('country_id')) {
            $query->whereHas('processes', function ($processesQuery) use ($request) {
                $processesQuery->whereIn('country_id', $request->input('country_id'));
            });
        }

        if ($request->filled('dosage')) {
            $query->whereHas('processes', function ($processesQuery) use ($request) {
                $processesQuery->where('dosage', $request->input('dosage'));
            });
        }

        return $query->select('name', 'id')
            ->orderBy('name')
            ->get();
    }

    private static function getInns($request)
    {
        $query = Inn::query();

        if ($request->filled('manufacturer_id')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->whereHas('processes')
                    ->whereHas('manufacturer', function ($manufacturersQuery) use ($request) {
                        $manufacturersQuery->whereIn('manufacturers.id', $request->input('manufacturer_id'));
                    });
            });
        }

        if ($request->filled('form_id')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->whereIn('form_id', $request->input('form_id'))
                    ->whereHas('processes');
            });
        }

        if ($request->filled('country_id')) {
            $query->whereHas('products.processes', function ($productsQuery) use ($request) {
                $productsQuery->whereIn('country_id', $request->input('country_id'));
            });
        }

        if ($request->filled('dosage')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->where('dosage', $request->input('dosage'))
                    ->whereHas('processes');;
            });
        }

        return $query->select('name', 'id')
            ->orderBy('name')
            ->get();
    }

    private static function getForms($request)
    {
        $query = ProductForm::query();

        if ($request->filled('manufacturer_id')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->whereHas('processes')
                    ->whereHas('manufacturer', function ($manufacturersQuery) use ($request) {
                        $manufacturersQuery->whereIn('manufacturers.id', $request->input('manufacturer_id'));
                    });
            });
        }

        if ($request->filled('inn_id')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->whereIn('inn_id', $request->input('inn_id'))
                    ->whereHas('processes');
            });
        }

        if ($request->filled('country_id')) {
            $query->whereHas('products.processes', function ($productsQuery) use ($request) {
                $productsQuery->whereIn('country_id', $request->input('country_id'));
            });
        }

        if ($request->filled('dosage')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->where('dosage', $request->input('dosage'))
                    ->whereHas('processes');;
            });
        }

        return $query->select('name', 'id')
            ->orderBy('name')
            ->get();
    }

    private static function getCountriesOrderedByProcessesCount($request)
    {
        $query = Country::query();

        if ($request->filled('manufacturer_id')) {
            $query->whereHas('processes.manufacturer', function ($manufacturersQuery) use ($request) {
                $manufacturersQuery->whereIn('manufacturers.id', $request->input('manufacturer_id'));
            });
        }

        if ($request->filled('inn_id')) {
            $query->whereHas('processes.product', function ($productsQuery) use ($request) {
                $productsQuery->whereIn('inn_id', $request->input('inn_id'));
            });
        }

        if ($request->filled('form_id')) {
            $query->whereHas('processes.product', function ($productsQuery) use ($request) {
                $productsQuery->whereIn('form_id', $request->input('form_id'));
            });
        }

        if ($request->filled('dosage')) {
            $query->whereHas('processes.product', function ($productsQuery) use ($request) {
                $productsQuery->where('dosage', $request->input('dosage'));
            });
        }

        return $query->orderByProcessesCount()
            ->select('code', 'id')
            ->get();
    }
}
