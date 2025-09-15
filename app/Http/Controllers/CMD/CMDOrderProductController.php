<?php

namespace App\Http\Controllers\CMD;

use App\Models\OrderProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderProductUpdateByCMDRequest;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class CMDOrderProductController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        OrderProduct::addDefaultCMDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = OrderProduct::onlySentToBdm()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = OrderProduct::filterQueryForRequest($query, $request);
        $joinedQuery = OrderProduct::addJoinsForOrdering($filteredQuery, $request); // add joins if joined ordering requested
        $records = OrderProduct::finalizeQueryForRequest($joinedQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(OrderProduct::SETTINGS_CMD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('CMD.order-products.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function edit(Request $request, OrderProduct $record)
    {
        return view('CMD.order-products.edit', compact('record'));
    }

    public function update(OrderProductUpdateByCMDRequest $request, OrderProduct $record)
    {
        $record->updateByCMDFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    /**
     * Ajax request
     */
    public function toggleProductionIsFinishedAttribute(Request $request)
    {
        $record = OrderProduct::withTrashed()->findOrFail($request->input('record_id'));
        $record->toggleProductionIsFinishedAttribute($request);

        return response()->json([
            'productionIsFinished' => $record->production_is_finished,
            'productionEndDate' => $record->production_end_date?->isoFormat('DD MMM Y'),
        ]);
    }

    /**
     * Ajax request
     */
    public function toggleIsReadyForShipmentAttribute(Request $request)
    {
        $record = OrderProduct::withTrashed()->findOrFail($request->input('record_id'));
        $record->toggleIsReadyForShipmentAttribute($request);

        return response()->json([
            'isReadyForShipment' => $record->is_ready_for_shipment_from_manufacturer,
            'readinessForShipmentDate' => $record->readiness_for_shipment_from_manufacturer_date?->isoFormat('DD MMM Y'),
        ]);
    }
}
