<?php

namespace App\Support\SmartFilters;

use App\Models\Country;
use App\Models\Inn;
use App\Models\Manufacturer;
use App\Models\ProcessStatus;
use App\Models\ProductForm;

class MadProcessesSmartFilter
{
    public static function getAllDependencies()
    {
        $requestData = self::getRequestData();

        return [
            'manufacturers' => self::getManufacturers($requestData),
            'inns' => self::getInns($requestData),
            'productForms' => self::getForms($requestData),
            'countriesOrderedByProcessesCount' => self::getCountriesOrderedByProcessesCount($requestData),
            'statuses' => self::getStatuses($requestData),
        ];
    }

    private static function getRequestData()
    {
        $request = request();

        return [
            'manufacturer_id' => $request->input('manufacturer_id', []),
            'inn_id' => $request->input('inn_id', []),
            'form_id' => $request->input('form_id', []),
            'country_id' => $request->input('country_id', []),
            'status_id' => $request->input('status_id', []),
            'dosage' => $request->input('dosage'),
        ];
    }

    private static function getManufacturers($requestData)
    {
        $query = Manufacturer::query();

        // Process
        $query->whereHas('processes', function ($processesQuery) use ($requestData) {
            if (!empty($requestData['country_id'])) {
                $processesQuery->whereIn('country_id', $requestData['country_id']);
            }

            if (!empty($requestData['status_id'])) {
                $processesQuery->whereIn('status_id', $requestData['status_id']);
            }

            // Product
            $processesQuery->whereHas('product', function ($productsQuery) use ($requestData) {
                if (!empty($requestData['inn_id'])) {
                    $productsQuery->whereIn('products.inn_id', $requestData['inn_id']);
                }

                if (!empty($requestData['form_id'])) {
                    $productsQuery->whereIn('products.form_id', $requestData['form_id']);
                }

                if ($requestData['dosage']) {
                    $productsQuery->where('products.dosage', $requestData['dosage']);
                }
            });
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
            if (!empty($requestData['manufacturer_id'])) {
                $productsQuery->whereIn('products.manufacturer_id', $requestData['manufacturer_id']);
            }

            if (!empty($requestData['form_id'])) {
                $productsQuery->whereIn('products.form_id', $requestData['form_id']);
            }

            if ($requestData['dosage']) {
                $productsQuery->where('products.dosage', $requestData['dosage']);
            }

            // Process
            $productsQuery->whereHas('processes', function ($processesQuery) use ($requestData) {
                if (!empty($requestData['country_id'])) {
                    $processesQuery->whereIn('processes.country_id', $requestData['country_id']);
                }

                if (!empty($requestData['status_id'])) {
                    $processesQuery->whereIn('processes.status_id', $requestData['status_id']);
                }
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
            if (!empty($requestData['manufacturer_id'])) {
                $productsQuery->whereIn('products.manufacturer_id', $requestData['manufacturer_id']);
            }

            if (!empty($requestData['inn_id'])) {
                $productsQuery->whereIn('products.inn_id', $requestData['inn_id']);
            }

            if ($requestData['dosage']) {
                $productsQuery->where('products.dosage', $requestData['dosage']);
            }

            // Process
            $productsQuery->whereHas('processes', function ($processesQuery) use ($requestData) {
                if (!empty($requestData['country_id'])) {
                    $processesQuery->whereIn('processes.country_id', $requestData['country_id']);
                }

                if (!empty($requestData['status_id'])) {
                    $processesQuery->whereIn('processes.status_id', $requestData['status_id']);
                }
            });
        });

        return $query->select('name', 'id')
            ->orderBy('name')
            ->get();
    }

    private static function getCountriesOrderedByProcessesCount($requestData)
    {
        $query = Country::query();

        $query->whereHas('processes', function ($processesQuery) use ($requestData) {
            // Process
            if (!empty($requestData['status_id'])) {
                $processesQuery->whereIn('processes.status_id', $requestData['status_id']);
            }

            // Product
            $processesQuery->whereHas('product', function ($productsQuery) use ($requestData) {
                if (!empty($requestData['manufacturer_id'])) {
                    $productsQuery->whereIn('products.manufacturer_id', $requestData['manufacturer_id']);
                }

                if (!empty($requestData['inn_id'])) {
                    $productsQuery->whereIn('products.inn_id', $requestData['inn_id']);
                }

                if (!empty($requestData['form_id'])) {
                    $productsQuery->whereIn('products.form_id', $requestData['form_id']);
                }

                if ($requestData['dosage']) {
                    $productsQuery->where('products.dosage', $requestData['dosage']);
                }
            });
        });

        return $query->orderByProcessesCount()
            ->select('code', 'id')
            ->get();
    }

    private static function getStatuses($requestData)
    {
        $query = ProcessStatus::query();

        $query->whereHas('processes', function ($processesQuery) use ($requestData) {
            // Process
            if (!empty($requestData['country_id'])) {
                $processesQuery->whereIn('processes.country_id', $requestData['country_id']);
            }

            // Product
            $processesQuery->whereHas('product', function ($productsQuery) use ($requestData) {
                if (!empty($requestData['manufacturer_id'])) {
                    $productsQuery->whereIn('products.manufacturer_id', $requestData['manufacturer_id']);
                }

                if (!empty($requestData['inn_id'])) {
                    $productsQuery->whereIn('products.inn_id', $requestData['inn_id']);
                }

                if (!empty($requestData['form_id'])) {
                    $productsQuery->whereIn('products.form_id', $requestData['form_id']);
                }

                if ($requestData['dosage']) {
                    $productsQuery->where('products.dosage', $requestData['dosage']);
                }
            });
        });

        return $query->orderBy('id', 'asc')
            ->select('name', 'id')
            ->get();
    }
}
