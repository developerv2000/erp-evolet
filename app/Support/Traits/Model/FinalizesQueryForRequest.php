<?php

namespace App\Support\Traits\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

trait FinalizesQueryForRequest
{
    /**
     * Finalizes the query by applying ordering, pagination, or retrieving data based on the specified action.
     *
     * @param Builder|Relation $query The Eloquent query builder or relation instance.
     * @param Request $request The request containing ordering and pagination parameters.
     * @param string $action Action to perform on the query: 'paginate', 'get', or 'query'.
     * @param string $defaultOrderBy Default column for ordering if none is specified.
     * @param string $defaultOrderType Default ordering type ('asc' or 'desc').
     * @return mixed
     */
    public static function finalizeQueryForRequest(
        Builder|Relation $query,
        Request $request,
        string $action = 'query',
        string $defaultOrderBy = 'created_at',
        string $defaultOrderType = 'desc',
    ) {
        // Apply primary and secondary ordering
        $query->orderBy($request->input('order_by', $defaultOrderBy), $request->input('order_type', $defaultOrderType))
            ->orderBy('id', $request->input('order_type', $defaultOrderType));

        // Handle pagination or retrieval based on the action parameter
        switch ($action) {
            case 'paginate':
                return $query->paginate(
                    $request->input('pagination_limit', 20),
                    ['*'],
                    'page',
                    $request->input('page', 1)
                )->appends($request->except(['page', 'reversed_order_url']));

            case 'get':
                return $query->get();

            case 'query':
            default:
                return $query;
        }
    }
}
