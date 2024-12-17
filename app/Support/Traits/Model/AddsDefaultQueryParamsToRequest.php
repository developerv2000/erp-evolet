<?php

namespace App\Support\Traits\Model;

use Illuminate\Http\Request;

trait AddsDefaultQueryParamsToRequest
{
    /**
     * Adds default ordering and pagination parameters to the given request object.
     *
     * @param \Illuminate\Http\Request $request The request object to merge parameters into.
     * @return void
     */
    public static function addDefaultQueryParamsToRequest(Request $request): void
    {
        // Define default parameters
        $defaultParams = [
            'order_by' => static::DEFAULT_ORDER_BY,
            'order_type' => static::DEFAULT_ORDER_TYPE,
            'pagination_limit' => static::DEFAULT_PAGINATION_LIMIT,
        ];

        // Merge default parameters into the request if they are not already present
        $request->mergeIfMissing($defaultParams);
    }
}
