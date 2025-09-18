<?php

namespace App\Http\Controllers\warehouse;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class WarehouseProductController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        OrderProduct::addDefaultELDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = OrderProduct::onlyReadyForShipmentFromManufacturer()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = OrderProduct::filterQueryForRequest($query, $request);
        $joinedQuery = OrderProduct::addJoinsForOrdering($filteredQuery, $request); // add joins if joined ordering requested
        $records = OrderProduct::finalizeQueryForRequest($joinedQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(OrderProduct::SETTINGS_WAREHOUSE_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('warehouse.products.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function edit(Request $request, OrderProduct $record)
    {
        return view('warehouse.products.edit', compact('record'));
    }

    public function update(Request $request, OrderProduct $record)
    {
        $record->updateFromWarehouseRequest($request);

        return redirect($request->input('previous_url'));
    }
}
