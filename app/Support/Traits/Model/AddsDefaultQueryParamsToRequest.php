<?php

namespace App\Support\Traits\Model;

use Illuminate\Http\Request;

trait AddsDefaultQueryParamsToRequest
{
    /**
     * Merge default ordering and pagination parameters into the request
     * if they are not already present.
     *
     * @param Request $request The request instance.
     * @param string $orderByConstant The name of the static constant for default order by.
     * @param string $orderTypeConstant The name of the static constant for default order direction.
     * @param string $paginationLimitConstant The name of the static constant for pagination limit.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the provided constant names do not exist on the class.
     */
    public static function addDefaultQueryParamsToRequest(
        Request $request,
        string $orderByConstant = 'DEFAULT_ORDER_BY',
        string $orderTypeConstant = 'DEFAULT_ORDER_TYPE',
        string $paginationLimitConstant = 'DEFAULT_PAGINATION_LIMIT'
    ): void {

        // Validate constants
        foreach ([$orderByConstant, $orderTypeConstant, $paginationLimitConstant] as $constant) {
            if (!defined(static::class . '::' . $constant)) {
                throw new \InvalidArgumentException("Constant '{$constant}' is not defined in " . static::class);
            }
        }

        $defaultParams = [
            'order_by' => static::{$orderByConstant},
            'order_type' => static::{$orderTypeConstant},
            'pagination_limit' => static::{$paginationLimitConstant},
        ];

        $request->mergeIfMissing($defaultParams);
    }
}
