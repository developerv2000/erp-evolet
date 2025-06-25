<?php

namespace App\Http\Controllers\PLPD;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreByPLPDRequest;
use App\Http\Requests\OrderUpdateByPLPDRequest;
use App\Models\Process;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use App\Support\Traits\Controller\RestoresModelRecords;
use Illuminate\Http\Request;

class PLPDOrderController extends Controller
{
    use DestroysModelRecords;
    use RestoresModelRecords;

    // used in multiple destroy/restore traits
    public static $model = Order::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Order::addDefaultPLPDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Order::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Order::filterQueryForRequest($query, $request);
        $records = Order::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Order::SETTINGS_PLPD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('PLPD.orders.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function trash(Request $request)
    {
        // Preapare request for valid model querying
        Order::addDefaultPLPDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get trashed finalized records paginated
        $query = Order::onlyTrashed()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Order::filterQueryForRequest($query, $request);
        $records = Order::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Order::SETTINGS_PLPD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('PLPD.orders.trash', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create()
    {
        return view('PLPD.orders.create');
    }

    /**
     * Ajax request on create & edit pages.
     */
    public function getReadyForOrderProcessesOfManufacturer(Request $request)
    {
        $manufacturerID = $request->input('manufacturer_id');
        $countryID = $request->input('country_id');
        $inputsIndex = $request->input('inputs_index');

        // Check if manufacturer and country are selected
        if (! $manufacturerID || ! $countryID) {
            return response()->json([
                'success' => false,
                'errorMessage' => __('Please select manufacturer and country'),
            ]);
        }

        $readyForOrderProcesses = Process::getReadyForOrderRecordsOfManufacturer($manufacturerID, $countryID, true);

        // Check if any records were found
        if ($readyForOrderProcesses->isEmpty()) {
            return response()->json([
                'success' => false,
                'errorMessage' => __('No products found for the given manufacturer and country'),
            ]);
        }

        // Return row with ready for order processes
        return response()->json([
            'success' => true,
            'row' => view(
                'PLPD.orders.partials.create-form-dynamic-rows-list-item',
                compact('readyForOrderProcesses', 'inputsIndex')
            )->render(),
        ]);
    }

    public function store(OrderStoreByPLPDRequest $request)
    {
        Order::createByPLPDFromRequest($request);

        return to_route('plpd.orders.index');
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function edit(Request $request, $record)
    {
        $record = Order::withTrashed()->findOrFail($record);

        return view('PLPD.orders.edit', compact('record'));
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function update(OrderUpdateByPLPDRequest $request, $record)
    {
        $record = Order::withTrashed()->findOrFail($record);
        $record->updateByPLPDFromRequest($request);

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
            'statusHTML' => view('components.tables.partials.td.order-status-badge', ['status' => $record->status])->render(),
        ]);
    }

    /**
     * Ajax request
     */
    public function toggleIsConfirmedAttribute(Request $request)
    {
        $record = Order::withTrashed()->findOrFail($request->input('record_id'));
        $record->toggleIsConfirmedAttribute($request);

        return response()->json([
            'isConfirmed' => $record->is_confirmed,
            'confirmationDate' => $record->confirmation_date?->isoFormat('DD MMM Y'),
            'statusHTML' => view('components.tables.partials.td.order-status-badge', ['status' => $record->status])->render(),
        ]);
    }
}
