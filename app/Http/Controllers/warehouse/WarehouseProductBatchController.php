<?php

namespace App\Http\Controllers\warehouse;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\ProductBatch;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class WarehouseProductBatchController extends Controller
{
    use DestroysModelRecords;

    // used in multiple destroy traits
    public static $model = ProductBatch::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        ProductBatch::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = ProductBatch::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = ProductBatch::filterQueryForRequest($query, $request);
        $records = ProductBatch::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(ProductBatch::SETTINGS_WAREHOUSE_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('warehouse.product-batches.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create(Request $request)
    {
        $product = OrderProduct::findOrFail($request->input('order_product_id'));

        if (!$product->can_create_batch) {
            abort(404);
        }

        return view('warehouse.product-batches.create', compact('product'));
    }

    public function store(Request $request)
    {
        $product = OrderProduct::findOrFail($request->input('order_product_id'));

        if (!$product->can_create_batch) {
            abort(404);
        }

        ProductBatch::createFromWarehouseRequest($request);

        return to_route('warehouse.product-batches.index', ['order_product_id[]' => $request->input('order_product_id')]);
    }

    public function edit(Request $request, ProductBatch $record)
    {
        return view('warehouse.product-batches.edit', compact('record'));
    }

    public function update(Request $request, ProductBatch $record)
    {
        $record->updateFromWarehouseRequest($request);

        return redirect($request->input('previous_url'));
    }

    public function requestSerialization(ProductBatch $record)
    {
        $record->serialization_request_date = now();
        $record->save();

        return redirect()->route('warehouse.product-batches.edit', $record->id);
    }
}
