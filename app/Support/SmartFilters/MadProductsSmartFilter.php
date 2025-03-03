<?php

namespace App\Support\SmartFilters;

use App\Models\Inn;
use App\Models\Manufacturer;
use App\Models\ProductForm;

class MadProductsSmartFilter
{
    public static function getAllDependencies()
    {
        $requestData = self::getRequestData();

        return [
            'manufacturers' => self::getManufacturers($requestData),
            'inns' => self::getInns($requestData),
            'productForms' => self::getForms($requestData),
        ];
    }

    private static function getRequestData()
    {
        $request = request();

        return [
            'manufacturer_id' => $request->input('manufacturer_id', []),
            'inn_id' => $request->input('inn_id', []),
            'form_id' => $request->input('form_id', []),
            'dosage' => $request->input('dosage'),
            'pack' => $request->input('pack'),
        ];
    }

    private static function getManufacturers($requestData)
    {
        $query = Manufacturer::query();

        // Product
        $query->whereHas('products', function ($productsQuery) use ($requestData) {
            if (!empty($requestData['inn_id'])) {
                $productsQuery->whereIn('products.inn_id', $requestData['inn_id']);
            }

            if (!empty($requestData['form_id'])) {
                $productsQuery->whereIn('products.form_id', $requestData['form_id']);
            }

            if ($requestData['dosage']) {
                $productsQuery->where('products.dosage', $requestData['dosage']);
            }

            if ($requestData['pack']) {
                $productsQuery->where('products.pack', $requestData['pack']);
            }
        });

        return $query->select('name', 'id')
            ->orderBy('name')
            ->get();
    }

    private static function getInns($requestData)
    {
        $query = Inn::query();

        // Product
        $query->whereHas('products', function ($productsQuery) use ($requestData) {
            if (!empty($requestData['form_id'])) {
                $productsQuery->whereIn('products.form_id', $requestData['form_id']);
            }

            if ($requestData['dosage']) {
                $productsQuery->where('products.dosage', $requestData['dosage']);
            }

            if ($requestData['pack']) {
                $productsQuery->where('products.pack', $requestData['pack']);
            }

            // Manufacturer
            $productsQuery->whereHas('manufacturer', function ($manufacturersQuery) use ($requestData) {
                $manufacturersQuery->whereIn('manufacturers.id', $requestData['manufacturer_id']);
            });
        });

        return $query->select('name', 'id')
            ->orderBy('name')
            ->get();
    }

    private static function getForms($requestData)
    {
        $query = ProductForm::query();

        // Product
        $query->whereHas('products', function ($productsQuery) use ($requestData) {
            if (!empty($requestData['inn_id'])) {
                $productsQuery->whereIn('products.inn_id', $requestData['inn_id']);
            }

            if ($requestData['dosage']) {
                $productsQuery->where('products.dosage', $requestData['dosage']);
            }

            if ($requestData['pack']) {
                $productsQuery->where('products.pack', $requestData['pack']);
            }

            // Manufacturer
            $productsQuery->whereHas('manufacturer', function ($manufacturersQuery) use ($requestData) {
                $manufacturersQuery->whereIn('manufacturers.id', $requestData['manufacturer_id']);
            });
        });

        return $query->select('name', 'id')
            ->orderBy('name')
            ->get();
    }
}
