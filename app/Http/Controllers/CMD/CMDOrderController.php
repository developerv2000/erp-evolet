<?php

namespace App\Http\Controllers\CMD;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderUpdateByCMDRequest;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class CMDOrderController extends Controller
{
    // Used in multiple destroy/restore traits
    public static $model = Order::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Order::addDefaultCMDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Order::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Order::filterQueryForRequest($query, $request);
        $records = Order::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Order::SETTINGS_CMD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('CMD.orders.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function edit(Request $request, Order $record)
    {
        return view('CMD.orders.edit', compact('record'));
    }

    public function update(OrderUpdateByCMDRequest $request, Order $record)
    {
        $record->updateByCMDFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    /**
     * Ajax request
     */
    public function toggleIsSentToBDMAttribute(Request $request)
    {
        $record = Order::withTrashed()->findOrFail($request->input('record_id'));
        $record->toggleIsSentToBDMAttribute($request);

        return response()->json([
            'isSentToBdm' => $record->is_sent_to_bdm,
            'sentToBdmDate' => $record->sent_to_bdm_date?->isoFormat('DD MMM Y'),
        ]);
    }
}
