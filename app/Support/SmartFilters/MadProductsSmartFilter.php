<?php

namespace App\Support\SmartFilters;

use App\Models\Inn;
use App\Models\Manufacturer;
use App\Models\ProductForm;
use Illuminate\Http\Request;

class MadProductsSmartFilter
{
    public static function getAllDependencies()
    {
        $request = request();

        return [
            'manufacturers' => self::getManufacturers($request),
            'inns' => self::getInns($request),
            'productForms' => self::getForms($request),
        ];
    }

    private static function getManufacturers(Request $request)
    {
        $query = Manufacturer::query();

        if ($request->filled('inn_id')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->whereIn('inn_id', $request->input('inn_id'));
            });
        }

        if ($request->filled('form_id')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->whereIn('form_id', $request->input('form_id'));
            });
        }

        if ($request->filled('dosage')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->where('dosage', $request->input('dosage'));
            });
        }

        if ($request->filled('pack')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->where('pack', $request->input('pack'));
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
            $query->whereHas('products.manufacturer', function ($manufacturersQuery) use ($request) {
                $manufacturersQuery->whereIn('manufacturers.id', $request->input('manufacturer_id'));
            });
        }

        if ($request->filled('form_id')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->whereIn('form_id', $request->input('form_id'));
            });
        }

        if ($request->filled('dosage')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->where('dosage', $request->input('dosage'));
            });
        }

        if ($request->filled('pack')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->where('pack', $request->input('pack'));
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
            $query->whereHas('products.manufacturer', function ($manufacturersQuery) use ($request) {
                $manufacturersQuery->whereIn('manufacturers.id', $request->input('manufacturer_id'));
            });
        }

        if ($request->filled('inn_id')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->whereIn('inn_id', $request->input('inn_id'));
            });
        }

        if ($request->filled('dosage')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->where('dosage', $request->input('dosage'));
            });
        }

        if ($request->filled('pack')) {
            $query->whereHas('products', function ($productsQuery) use ($request) {
                $productsQuery->where('pack', $request->input('pack'));
            });
        }

        return $query->select('name', 'id')
            ->orderBy('name')
            ->get();
    }
}
