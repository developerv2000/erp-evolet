<?php

namespace App\Support\Traits\Model;

use Illuminate\Http\Request;

trait AddsQueryParamsToRequest
{
    /**
     * Adds default ordering and pagination parameters to the given request object for front.
     *
     * @param \Illuminate\Http\Request $request The request object to merge parameters into.
     * @return void
     */
    public static function addFrontQueryParamsToRequest(Request $request): void
    {
        // Define default parameters based on context (dashboard or front-end)
        $defaultParams = [
            'order_by' => static::DEFAULT_FRONT_ORDER_BY,
            'order_type' => static::DEFAULT_FRONT_ORDER_TYPE,
            'pagination_limit' => static::DEFAULT_FRONT_PAGINATION_LIMIT,
        ];

        // Merge default parameters into the request if they are not already present
        $request->mergeIfMissing($defaultParams);
    }

    /**
     * Adds default ordering and pagination parameters to the given request object for dashboard.
     *
     * @param \Illuminate\Http\Request $request The request object to merge parameters into.
     * @return void
     */
    public static function addDashboardQueryParamsToRequest(Request $request): void
    {
        // Define default parameters based on context (dashboard or front-end)
        $defaultParams = [
            'order_by' => static::DEFAULT_DASHBOARD_ORDER_BY,
            'order_type' => static::DEFAULT_DASHBOARD_ORDER_TYPE,
            'pagination_limit' => static::DEFAULT_DASHBOARD_PAGINATION_LIMIT,
        ];

        // Merge default parameters into the request if they are not already present
        $request->mergeIfMissing($defaultParams);
    }
}
