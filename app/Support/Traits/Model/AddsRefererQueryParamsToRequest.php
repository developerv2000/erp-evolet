<?php

namespace App\Support\Traits\Model;

use Illuminate\Http\Request;

trait AddsRefererQueryParamsToRequest
{
    /**
     * Adds query parameters from the referer URL into the request object.
     *
     * Used on export routes.
     *
     * @param Request $request
     * @return void
     */
    public static function addRefererQueryParamsToRequest(Request $request): void
    {
        $refererUrl = $request->header('referer');

        if (!$refererUrl) {
            return; // Exit early if there is no referer URL
        }

        // Extract query string from the referer URL
        $queryString = parse_url($refererUrl, PHP_URL_QUERY);

        if (!$queryString) {
            return; // Exit early if there are no query parameters
        }

        // Parse query string into an associative array
        $queryParams = [];
        parse_str($queryString, $queryParams);

        // Merge query parameters into the request object
        $request->mergeIfMissing($queryParams);
    }
}
