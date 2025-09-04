<?php

namespace App\Http\Controllers\ELD;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderProductUpdateByELDRequest;
use App\Models\OrderProduct;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class ELDOrderProductController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        OrderProduct::addDefaultELDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = OrderProduct::onlyReadyForShipment()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = OrderProduct::filterQueryForRequest($query, $request);
        $joinedQuery = OrderProduct::addJoinsForOrdering($filteredQuery, $request); // add joins if joined ordering requested
        $records = OrderProduct::finalizeQueryForRequest($joinedQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(OrderProduct::SETTINGS_ELD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('ELD.order-products.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function edit(Request $request, OrderProduct $record)
    {
        return view('ELD.order-products.edit', compact('record'));
    }

    public function update(OrderProductUpdateByELDRequest $request, OrderProduct $record)
    {
        $record->updateByELDFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    public function startShipmentFromManufacturer(OrderProduct $record)
    {
        $record->shipment_from_manufacturer_start_date = now();
        $record->save();

        return redirect()->route('eld.order-products.edit', $record->id);
    }

    public function requestDeliveryToWarehouse(OrderProduct $record)
    {
        $record->delivery_to_warehouse_request_date = now();
        $record->save();

        return redirect()->route('eld.order-products.edit', $record->id);
    }
}
