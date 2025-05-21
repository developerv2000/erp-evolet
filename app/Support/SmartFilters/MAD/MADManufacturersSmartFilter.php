<?php

namespace App\Support\SmartFilters\MAD;

use App\Models\Country;
use App\Models\Manufacturer;
use App\Models\User;

class MADManufacturersSmartFilter
{
    public static function getAllDependencies()
    {
        $requestData = self::getRequestData();

        return [
            'analystUsers' => self::getAnalysts($requestData),
            'countriesOrderedByName' => self::getCountriesOrderedByName($requestData),
            'manufacturers' => self::getManufacturers($requestData),
        ];
    }

    private static function getRequestData()
    {
        $request = request();

        return [
            'analyst_user_id' => $request->input('analyst_user_id'),
            'country_id' => $request->input('country_id', []),
            'manufacturer_id' => $request->input('id', []),
        ];
    }

    private static function getAnalysts($requestData)
    {
        $query = User::onlyMADAnalysts();

        // Manufacturer
        $query->whereHas('analystManufacturers', function ($manufacturersQuery) use ($requestData) {
            if (!empty($requestData['country_id'])) {
                $manufacturersQuery->whereIn('country_id', $requestData['country_id']);
            }

            if (!empty($requestData['manufacturer_id'])) {
                $manufacturersQuery->whereIn('id', $requestData['manufacturer_id']);
            }
        });

        return $query->select('name', 'id')
            ->orderBy('name')
            ->get();
    }

    private static function getCountriesOrderedByName($requestData)
    {
        $query = Country::query();

        // Manufacturer
        $query->whereHas('manufacturers', function ($manufacturersQuery) use ($requestData) {
            if (!empty($requestData['analyst_user_id'])) {
                $manufacturersQuery->where('analyst_user_id', $requestData['analyst_user_id']);
            }

            if (!empty($requestData['manufacturer_id'])) {
                $manufacturersQuery->whereIn('id', $requestData['manufacturer_id']);
            }
        });

        return $query->select('name', 'id')
            ->orderBy('name')
            ->get();
    }

    private static function getManufacturers($requestData)
    {
        $query = Manufacturer::query();

        if (!empty($requestData['analyst_user_id'])) {
            $query->where('analyst_user_id', $requestData['analyst_user_id']);
        }

        if (!empty($requestData['country_id'])) {
            $query->whereIn('country_id', $requestData['country_id']);
        }

        return $query->select('name', 'id')
            ->orderBy('name')
            ->get();
    }
}
