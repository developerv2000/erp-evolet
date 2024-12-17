<?php

namespace App\Support\Helpers;

use Illuminate\Http\Request;

class UrlHelper
{
    /**
     * Add current URL with the reversed 'order_type' and removed 'order_by' from query parameters to request.
     *
     * Mainlu used in order links of dashboard tables.
     */
    public static function addUrlWithReversedOrderTypeToRequest($request)
    {
        $request->mergeIfMissing([
            'reversed_order_url' => self::generateUrlWithReversedOrderType($request),
        ]);
    }

    /**
     * Generates a URL with the reversed 'order_type' and removed 'order_by' from query parameters.
     *
     * @param Request $request
     * @return string The modified URL with reversed sorting order and without 'order_by'.
     */
    private static function generateUrlWithReversedOrderType(Request $request): string
    {
        // Get the existing query parameters from the request
        $queryParams = $request->query();

        // Remove 'order_by' if it exists
        GeneralHelper::removeArrayKey($queryParams, 'order_by');

        // Reverse the 'order_type' (toggle between 'asc' and 'desc')
        $queryParams['order_type'] = $request->get('order_type', 'asc') === 'asc' ? 'desc' : 'asc';

        // Build and return the URL with updated query parameters
        return $request->url() . '?' . http_build_query($queryParams);
    }
}
