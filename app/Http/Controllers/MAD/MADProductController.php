<?php

namespace App\Http\Controllers\MAD;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Atx;
use App\Models\Product;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\SmartFilters\MadProductsSmartFilter;
use App\Support\Traits\Controller\DestroysModelRecords;
use App\Support\Traits\Controller\RestoresModelRecords;
use Illuminate\Http\Request;

class MADProductController extends Controller
{
    use DestroysModelRecords;
    use RestoresModelRecords;

    // used in multiple destroy/restore traits
    public static $model = Product::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Product::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Product::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Product::filterQueryForRequest($query, $request);
        $records = Product::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Product::SETTINGS_MAD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('products.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function getSmartFilterDependencies()
    {
        return MadProductsSmartFilter::getAllDependencies();
    }

    public function trash(Request $request)
    {
        // Preapare request for valid model querying
        Product::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get trashed finalized records paginated
        $query = Product::onlyTrashed()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Product::filterQueryForRequest($query, $request);
        $records = Product::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Product::SETTINGS_MAD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('products.trash', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create()
    {
        return view('products.create');
    }

    /**
     * Get similar records based on the provided request data.
     *
     * Used on AJAX requests to retrieve similar records, on the products create form.
     */
    public function getSimilarRecordsForRequest(Request $request)
    {
        $similarRecords = Product::getSimilarRecordsForRequest($request);

        return view('products.partials.similar-records', compact('similarRecords'));
    }

    /**
     * Get specific product atx inputs.
     *
     * Used on AJAX requests to retrieve atx inputs of product, on the products create/edit forms.
     */
    public function getATXInputs(Request $request)
    {
        $atx = Atx::where('inn_id', $request->input('inn_id'))
            ->where('form_id', $request->input('form_id'))
            ->first();

        return view('products.partials.atx-inputs', compact('atx'));
    }

    /**
     * Get 'dosage' and 'pack' form row, for multiple records store.
     *
     * Used on AJAX requests to retrieve 'dosage' and 'pack' form row on products create form.
     */
    public function getDynamicRowsListItemInputs(Request $request)
    {
        $inputsIndex = $request->input('inputs_index');

        return view('products.partials.create-form-dynamic-rows-list-item', compact('inputsIndex'));
    }

    public function store(Request $request)
    {
        // Create or update atx before storing products
        Atx::syncAtxWithProduct($request);

        // Store products
        Product::createMultipleRecordsFromRequest($request);

        return to_route('products.index');
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function edit(Request $request, $record)
    {
        $record = Product::withTrashed()->findOrFail($record);

        return view('products.edit', compact('record'));
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function update(ProductUpdateRequest $request, $record)
    {
        // Create or update atx before updating products
        Atx::syncAtxWithProduct($request);

        // Update product
        $record = Product::withTrashed()->findOrFail($record);
        $record->updateFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    public function exportAsExcel(Request $request)
    {
        // Preapare request for valid model querying
        Product::addRefererQueryParamsToRequest($request);
        Product::addDefaultQueryParamsToRequest($request);

        // Get finalized records query
        $query = Product::withRelationsForExport();
        $filteredQuery = Product::filterQueryForRequest($query, $request);
        $records = Product::finalizeQueryForRequest($filteredQuery, $request, 'query');

        // Export records
        return Product::exportRecordsAsExcel($records);
    }
}
