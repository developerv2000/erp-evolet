<?php

namespace App\Http\Controllers\PLPD;

use App\Models\OrderProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderProductStoreByPLPDRequest;
use App\Http\Requests\OrderProductUpdateByPLPDRequest;
use App\Models\Order;
use App\Models\Process;
use App\Models\SerializationType;
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
        $joinedQuery = OrderProduct::addJoinsForOrdering($filteredQuery, $request); // add joins if joined ordering requested
        $records = OrderProduct::finalizeQueryForRequest($joinedQuery, $request, 'paginate');

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

        $serializationTypes = SerializationType::defaultOrdered()->get();

        return view('PLPD.order-products.create', compact('order', 'readyForOrderProcesses', 'serializationTypes'));
    }

    public function store(OrderProductStoreByPLPDRequest $request)
    {
        OrderProduct::createByPLPDFromRequest($request);

        return to_route('plpd.order-products.index', ['order_id' => $request->order_id]);
    }

    public function edit(Request $request, OrderProduct $record)
    {
        $readyForOrderProcesses = Process::getReadyForOrderRecordsOfManufacturer(
            $record->order->manufacturer_id,
            $record->order->country_id,
            true
        );

        $processWithItSimilarRecords = $record->process->getProcessWithItSimilarRecordsForOrder(true);
        $serializationTypes = SerializationType::defaultOrdered()->get();

        return view('PLPD.order-products.edit', compact('record', 'readyForOrderProcesses', 'processWithItSimilarRecords', 'serializationTypes'));
    }

    public function update(OrderProductUpdateByPLPDRequest $request, OrderProduct $record)
    {
        $record->updateByPLPDFromRequest($request);

        return redirect($request->input('previous_url'));
    }
}
