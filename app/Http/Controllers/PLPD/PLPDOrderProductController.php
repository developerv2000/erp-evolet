<?php

namespace App\Http\Controllers\PLPD;

use App\Models\OrderProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderProductStoreByPLPDRequest;
use App\Http\Requests\OrderProductUpdateByPLPDRequest;
use App\Models\Order;
use App\Models\Process;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class PLPDOrderProductController extends Controller
{
    use DestroysModelRecords;

    // used in multiple destroy/restore traits
    public static $model = OrderProduct::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        OrderProduct::addDefaultPLPDQueryParamsToRequest($request);
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

    public function create(Request $request)
    {
        $order = Order::withTrashed()->findOrFail($request->order_id);

        $readyForOrderProcesses = Process::getReadyForOrderRecordsOfManufacturer(
            $order->manufacturer_id,
            $order->country_id,
            true
        );

        return view('PLPD.order-products.create', compact('order', 'readyForOrderProcesses'));
    }

    public function store(OrderProductStoreByPLPDRequest $request)
    {
        OrderProduct::createByPLPDFromRequest($request);

        return to_route('plpd.order-products.index', ['order_id' => $request->order_id]);
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function edit(Request $request, $record)
    {
        $record = OrderProduct::withTrashed()->findOrFail($record);

        $readyForOrderProcesses = Process::getReadyForOrderRecordsOfManufacturer(
            $record->order->manufacturer_id,
            $record->order->country_id,
            true
        );

        $processWithItSimilarRecords = $record->process->getProcessWithItSimilarRecordsForOrder(true);

        return view('PLPD.order-products.edit', compact('record', 'readyForOrderProcesses', 'processWithItSimilarRecords'));
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function update(OrderProductUpdateByPLPDRequest $request, $record)
    {
        $record = OrderProduct::withTrashed()->findOrFail($record);
        $record->updateByPLPDFromRequest($request);

        return redirect($request->input('previous_url'));
    }
}
