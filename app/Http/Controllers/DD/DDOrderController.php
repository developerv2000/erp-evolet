<?php

namespace App\Http\Controllers\DD;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderUpdateByDDRequest;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class DDOrderController extends Controller
{
    // Used in multiple destroy/restore traits
    public static $model = Order::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Order::addDefaultDDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Order::onlySentToManufacturer()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Order::filterQueryForRequest($query, $request);
        $records = Order::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Order::SETTINGS_DD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('DD.orders.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function edit(Request $request, Order $record)
    {
        return view('DD.orders.edit', compact('record'));
    }

    public function update(OrderUpdateByDDRequest $request, Order $record)
    {
        $record->updateByDDFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    /**
     * Ajax request
     */
    public function toggleLayoutApprovedAttribute(Request $request)
    {
        $record = Order::withTrashed()->findOrFail($request->input('record_id'));
        $record->toggleLayoutApprovedAttribute($request);

        return response()->json([
            'layoutisApproved' => $record->layout_is_approved,
            'layoutApprovedDate' => $record->layout_approved_date?->isoFormat('DD MMM Y'),
            // 'statusHTML' => view('components.tables.partials.td.order-status-badge', ['status' => $record->status])->render(),
        ]);
    }
}
