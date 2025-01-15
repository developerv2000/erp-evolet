<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use App\Support\Traits\Controller\RestoresModelRecords;
use Illuminate\Http\Request;

class ProductController extends Controller
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
     * Used for AJAX requests to retrieve similar records, on the products create form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSimilarRecordsForRequest(Request $request)
    {
        $similarRecords = Product::getSimilarRecordsForRequest($request);

        return view('products.partials.similar-records', compact('similarRecords'));
    }

    public function store(ProductStoreRequest $request)
    {
        Product::createFromRequest($request);

        return to_route('products.index');
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function edit(Request $request, $record)
    {
        $record = Product::withTrashed()->findOrFail($record);
        $record->loadBasicNonBelongsToRelations();

        return view('products.edit', compact('record'));
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function update(ProductUpdateRequest $request, $record)
    {
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
