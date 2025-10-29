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
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Order::addDefaultCMDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Order::onlySentToBdm()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Order::filterQueryForRequest($query, $request);
        $records = Order::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Order::SETTINGS_CMD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('CMD.orders.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function edit(Request $request, Order $record)
    {
        $record->load([
            'products' => function ($productsQuery) {
                $productsQuery->with([
                    'process' => function ($processQuery) {
                        $processQuery->withRelationsForOrderProduct();
                        // ->withOnlyRequiredSelectsForOrderProduct(); not used because 'agreed_price' also required
                    },
                ]);
            }
        ]);

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
    public function toggleIsSentToConfirmationAttribute(Request $request)
    {
        $record = Order::withTrashed()->findOrFail($request->input('record_id'));
        $record->toggleIsSentToConfirmationAttribute($request);

        return response()->json([
            'isSentToConfirmation' => $record->is_sent_to_confirmation,
            'sentToConfirmationDate' => $record->sent_to_confirmation_date?->isoFormat('DD MMM Y'),
            'statusHTML' => view('components.tables.partials.td.order-status-badge', ['status' => $record->status])->render(),
        ]);
    }

    /**
     * Ajax request
     */
    public function toggleIsSentToManufacturerAttribute(Request $request)
    {
        $record = Order::withTrashed()->findOrFail($request->input('record_id'));
        $record->toggleIsSentToManufacturerAttribute($request);

        return response()->json([
            'isSentToManufacturer' => $record->is_sent_to_manufacturer,
            'sentToManufacturerDate' => $record->sent_to_manufacturer_date?->isoFormat('DD MMM Y'),
            'statusHTML' => view('components.tables.partials.td.order-status-badge', ['status' => $record->status])->render(),
        ]);
    }

    /**
     * Ajax request
     */
    public function toggleProductionIsStartedAttribute(Request $request)
    {
        $record = Order::withTrashed()->findOrFail($request->input('record_id'));
        $record->toggleProductionIsStartedAttribute($request);

        return response()->json([
            'productionIsStarted' => $record->production_is_started,
            'productionStartDate' => $record->production_start_date?->isoFormat('DD MMM Y'),
            'statusHTML' => view('components.tables.partials.td.order-status-badge', ['status' => $record->status])->render(),
        ]);
    }
}
