<?php

namespace App\Http\Controllers\DD;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderProductUpdateByDDRequest;
use App\Models\OrderProduct;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class DDOrderProductController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        OrderProduct::addDefaultDDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = OrderProduct::onlySentToManufacturer()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = OrderProduct::filterQueryForRequest($query, $request);
        $joinedQuery = OrderProduct::addJoinsForOrdering($filteredQuery, $request); // add joins if joined ordering requested
        $records = OrderProduct::finalizeQueryForRequest($joinedQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(OrderProduct::SETTINGS_DD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('DD.order-products.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function edit(Request $request, OrderProduct $record)
    {
        return view('DD.order-products.edit', compact('record'));
    }

    public function update(OrderProductUpdateByDDRequest $request, OrderProduct $record)
    {
        $record->updateByDDFromRequest($request);

        return redirect($request->input('previous_url'));
    }
}
