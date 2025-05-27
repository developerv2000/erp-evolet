<?php

namespace App\Http\Controllers\PLPD;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\MarketingAuthorizationHolder;
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
        Order::addDefaultQueryParamsToRequest($request);
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
        Order::addDefaultQueryParamsToRequest($request);
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
     * Ajax request
     */
    public function getDynamicRowsListItemInputsOnCreate(Request $request)
    {
        $inputsIndex = $request->input('inputs_index');
        $manufacturerID = $request->input('manufacturer_id');
        $countryID = $request->input('country_id');

        $readyForOrderProcesses = Process::onlyReadyForOrder()
            ->withRelationsForOrder()
            ->withOnlyRequiredSelectsForOrder()
            ->whereHas('product.manufacturer', function ($manufacturerQuery) use ($manufacturerID) {
                $manufacturerQuery->where('manufacturers.id', $manufacturerID);
            })
            ->where('country_id', $countryID)
            ->get();

        if ($readyForOrderProcesses->isEmpty()) {
            return response()->json([
                'products_found' => false,
                'message' => __('No products found for the given manufacturer and country') . '.',
            ]);
        }

        $MAHs = MarketingAuthorizationHolder::orderByName()->get();

        return response()->json([
            'products_found' => true,
            'inputs' => view('PLPD.orders.partials.create-form-dynamic-rows-list-item', compact(
                'readyForOrderProcesses',
                'MAHs',
                'inputsIndex'
            ))->render(),
        ]);
    }

    public function store(OrderStoreRequest $request)
    {
        Order::createFromRequest($request);

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
    public function update(OrderUpdateRequest $request, $record)
    {
        $record = Order::withTrashed()->findOrFail($record);
        $record->updateFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    public function exportAsExcel(Request $request)
    {
        // Preapare request for valid model querying
        Order::addRefererQueryParamsToRequest($request);
        Order::addDefaultQueryParamsToRequest($request);

        // Get finalized records query
        $query = Order::withRelationsForExport();
        $filteredQuery = Order::filterQueryForRequest($query, $request);
        $records = Order::finalizeQueryForRequest($filteredQuery, $request, 'query');

        // Export records
        return Order::exportRecordsAsExcel($records);
    }
}
