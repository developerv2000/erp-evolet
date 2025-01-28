<?php

namespace App\Support\Helpers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueryFilterHelper
{
    /**
     * Apply a set of filter functions based on attribute configuration.
     *
     * @param Builder $query
     * @param Request $request
     * @param array $config
     * @return Builder
     */
    public static function applyFilters(Builder $query, Request $request, array $config)
    {
        $query = self::filterWhereEqual($request, $query, $config['whereEqual'] ?? []);
        $query = self::filterWhereIn($request, $query, $config['whereIn'] ?? []);
        $query = self::filterWhereNotIn($request, $query, $config['whereNotIn'] ?? []);
        $query = self::filterDate($request, $query, $config['date'] ?? []);
        $query = self::filterLike($request, $query, $config['like'] ?? []);
        $query = self::filterDateRange($request, $query, $config['dateRange'] ?? []);
        $query = self::filterWhereRelationDateRangeAmbiguous($request, $query, $config['relationDateRangeAmbiguous'] ?? []);
        $query = self::filterBelongsToMany($request, $query, $config['belongsToMany'] ?? []);
        $query = self::filterRelationEqual($request, $query, $config['relationEqual'] ?? []);
        $query = self::filterRelationIn($request, $query, $config['relationIn'] ?? []);
        $query = self::filterRelationLike($request, $query, $config['relationLike'] ?? []);
        $query = self::filterRelationEqualAmbiguous($request, $query, $config['relationEqualAmbiguous'] ?? []);
        $query = self::filterRelationInAmbiguous($request, $query, $config['relationInAmbiguous'] ?? []);

        return $query;
    }

    public static function filterWhereEqual(Request $request, Builder $query, array $attributes): Builder
    {
        foreach ($attributes as $attribute) {
            if ($request->filled($attribute)) {
                $query->where($attribute, $request->input($attribute));
            }
        }
        return $query;
    }

    public static function filterWhereIn(Request $request, Builder $query, array $attributes): Builder
    {
        foreach ($attributes as $attribute) {
            if ($request->filled($attribute)) {
                $query->whereIn($attribute, $request->input($attribute));
            }
        }
        return $query;
    }

    public static function filterWhereNotIn(Request $request, Builder $query, array $attributes): Builder
    {
        foreach ($attributes as $attribute) {
            if ($request->filled($attribute['inputName'])) {
                $query->whereNotIn($attribute['attributeName'], $request->input($attribute['inputName']));
            }
        }
        return $query;
    }

    public static function filterDate(Request $request, Builder $query, array $attributes): Builder
    {
        foreach ($attributes as $attribute) {
            if ($request->filled($attribute)) {
                $query->whereDate($attribute, $request->input($attribute));
            }
        }
        return $query;
    }

    public static function filterLike(Request $request, Builder $query, array $attributes): Builder
    {
        foreach ($attributes as $attribute) {
            if ($request->filled($attribute)) {
                $query->where($attribute, 'LIKE', '%' . $request->input($attribute) . '%');
            }
        }
        return $query;
    }

    public static function filterDateRange(Request $request, Builder $query, array $attributes): Builder
    {
        foreach ($attributes as $attribute) {
            if ($request->filled($attribute)) {
                [$fromDate, $toDate] = explode(' - ', $request->input($attribute));

                // Parse dates to match valid timestamp format
                $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
                $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

                $query->whereDate($attribute, '>=', $fromDate)
                    ->whereDate($attribute, '<', $toDate);
            }
        }
        return $query;
    }

    public static function filterWhereRelationDateRangeAmbiguous(Request $request, Builder $query, array $relations): Builder
    {
        foreach ($relations as $relation) {
            if ($request->filled($relation['attribute'])) {
                [$fromDate, $toDate] = explode(' - ', $request->input($relation['attribute']));

                // Parse dates to match valid timestamp format
                $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
                $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

                $query->whereHas($relation['name'], function ($q) use ($fromDate, $toDate, $relation) {
                    $q->whereDate($relation['ambiguousAttribute'], '>=', $fromDate)
                        ->whereDate($relation['ambiguousAttribute'], '<', $toDate);
                });
            }
        }
        return $query;
    }


    public static function filterBelongsToMany(Request $request, Builder $query, array $relationNames): Builder
    {
        foreach ($relationNames as $relationName) {
            if ($request->filled($relationName)) {
                $query->whereHas($relationName, function ($q) use ($request, $relationName) {
                    $q->whereIn('id', $request->input($relationName));
                });
            }
        }
        return $query;
    }

    public static function filterRelationEqual(Request $request, Builder $query, array $relations): Builder
    {
        foreach ($relations as $relation) {
            if ($request->filled($relation['attribute'])) {
                $query->whereHas($relation['name'], function ($q) use ($request, $relation) {
                    $q->where($relation['attribute'], $request->input($relation['attribute']));
                });
            }
        }
        return $query;
    }

    public static function filterRelationIn(Request $request, Builder $query, array $relations): Builder
    {
        foreach ($relations as $relation) {
            if ($request->filled($relation['attribute'])) {
                $query->whereHas($relation['name'], function ($q) use ($request, $relation) {
                    $q->whereIn($relation['attribute'], $request->input($relation['attribute']));
                });
            }
        }
        return $query;
    }

    public static function filterRelationLike(Request $request, Builder $query, array $relations): Builder
    {
        foreach ($relations as $relation) {
            if ($request->filled($relation['attribute'])) {
                $query->whereHas($relation['name'], function ($q) use ($request, $relation) {
                    $q->where($relation['attribute'], 'LIKE', '%' . $request->input($relation['attribute']) . '%');
                });
            }
        }
        return $query;
    }

    public static function filterRelationEqualAmbiguous(Request $request, Builder $query, array $relations): Builder
    {
        foreach ($relations as $relation) {
            if ($request->filled($relation['attribute'])) {
                $query->whereHas($relation['name'], function ($q) use ($request, $relation) {
                    $q->where($relation['ambiguousAttribute'], $request->input($relation['attribute']));
                });
            }
        }
        return $query;
    }

    public static function filterRelationInAmbiguous(Request $request, Builder $query, array $relations): Builder
    {
        foreach ($relations as $relation) {
            if ($request->filled($relation['attribute'])) {
                $query->whereHas($relation['name'], function ($q) use ($request, $relation) {
                    $q->whereIn($relation['ambiguousAttribute'], $request->input($relation['attribute']));
                });
            }
        }
        return $query;
    }
}
