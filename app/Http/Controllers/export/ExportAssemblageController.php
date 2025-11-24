<?php

namespace App\Http\Controllers\export;

use App\Http\Controllers\Controller;
use App\Models\Assemblage;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class ExportAssemblageController extends Controller
{
    use DestroysModelRecords;

    // used in multiple destroy traits
    public static $model = Assemblage::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Assemblage::addDefaultExportQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Assemblage::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Assemblage::filterQueryForRequest($query, $request);
        $records = Assemblage::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Assemblage::SETTINGS_EXPORT_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('export.assemblages.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create(Request $request)
    {
        return view('export.assemblages.create');
    }

    /**
     * Used on AJAX requests to retrieve form row inputs on create form.
     */
    public function getDynamicRowsListItemInputs(Request $request)
    {
        $inputsIndex = $request->input('inputs_index');

        return view('warehouse.product-batches.partials.create-form-dynamic-rows-list-item', compact('inputsIndex'));
    }

    public function store(Request $request)
    {
        $product = OrderProduct::findOrFail($request->input('order_product_id'));

        if (!$product->can_create_batch) {
            abort(404);
        }

        Assemblage::createFromWarehouseRequest($request, $product);

        return to_route('warehouse.product-batches.index', ['order_product_id' => $request->input('order_product_id')]);
    }

    public function edit(Request $request, Assemblage $record)
    {
        return view('warehouse.product-batches.edit', compact('record'));
    }

    public function update(Request $request, Assemblage $record)
    {
        $record->updateFromWarehouseRequest($request);

        return redirect($request->input('previous_url'));
    }

    public function requestSerialization(Assemblage $record)
    {
        $record->serialization_request_date = now();
        $record->save();

        return redirect()->route('warehouse.product-batches.edit', $record->id);
    }
}
