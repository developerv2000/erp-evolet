<?php

namespace App\Http\Controllers\PLPD;

use App\Models\OrderProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderProductStoreRequest;
use App\Http\Requests\OrderProductUpdateRequest;
use App\Models\Order;
use App\Models\Process;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use App\Support\Traits\Controller\RestoresModelRecords;
use Illuminate\Http\Request;

class PLPDOrderProductController extends Controller
{
    use DestroysModelRecords;
    use RestoresModelRecords;

    // used in multiple destroy/restore traits
    public static $model = OrderProduct::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        OrderProduct::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = OrderProduct::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = OrderProduct::filterQueryForRequest($query, $request);
        $records = OrderProduct::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(OrderProduct::SETTINGS_PLPD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('PLPD.order-products.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function trash(Request $request)
    {
        // Preapare request for valid model querying
        OrderProduct::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get trashed finalized records paginated
        $query = OrderProduct::onlyTrashed()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = OrderProduct::filterQueryForRequest($query, $request);
        $records = OrderProduct::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(OrderProduct::SETTINGS_PLPD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('PLPD.order-products.trash', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create(Request $request)
    {
        $order = Order::findOrFail($request->input('order_id'));

        $readyForOrderProcesses = Process::onlyReadyForOrder()
            ->withRelationsForOrder()
            ->withOnlyRequiredSelectsForOrder()
            ->whereHas('product.manufacturer', function ($manufacturerQuery) use ($order) {
                $manufacturerQuery->where('manufacturers.id', $order->manufacturer_id);
            })
            ->where('country_id', $order->country_id)
            ->get();

        return view('PLPD.order-products.create', compact('order', 'readyForOrderProcesses'));
    }

    public function store(OrderProductStoreRequest $request)
    {
        OrderProduct::createFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function edit(Request $request, $record)
    {
        $record = OrderProduct::withTrashed()->findOrFail($record);

        return view('PLPD.order-products.edit', compact('record'));
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function update(OrderProductUpdateRequest $request, $record)
    {
        $record = OrderProduct::withTrashed()->findOrFail($record);
        $record->updateFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    public function exportAsExcel(Request $request)
    {
        // Preapare request for valid model querying
        OrderProduct::addRefererQueryParamsToRequest($request);
        OrderProduct::addDefaultQueryParamsToRequest($request);

        // Get finalized records query
        $query = OrderProduct::withRelationsForExport();
        $filteredQuery = OrderProduct::filterQueryForRequest($query, $request);
        $records = OrderProduct::finalizeQueryForRequest($filteredQuery, $request, 'query');

        // Export records
        return OrderProduct::exportRecordsAsExcel($records);
    }
}
