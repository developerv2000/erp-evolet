<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductSearchStoreRequest;
use App\Http\Requests\ProductSearchUpdateRequest;
use App\Models\ProductSearch;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use App\Support\Traits\Controller\RestoresModelRecords;
use Illuminate\Http\Request;

class ProductSearchController extends Controller
{
    use DestroysModelRecords;
    use RestoresModelRecords;

    // used in multiple destroy/restore traits
    public static $model = ProductSearch::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        ProductSearch::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = ProductSearch::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = ProductSearch::filterQueryForRequest($query, $request);
        $records = ProductSearch::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(ProductSearch::SETTINGS_MAD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('product-searches.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function trash(Request $request)
    {
        // Preapare request for valid model querying
        ProductSearch::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get trashed finalized records paginated
        $query = ProductSearch::onlyTrashed()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = ProductSearch::filterQueryForRequest($query, $request);
        $records = ProductSearch::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(ProductSearch::SETTINGS_MAD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('product-searches.trash', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create()
    {
        return view('product-searches.create');
    }

    /**
     * Get similar records based on the provided request data.
     *
     * Used for AJAX requests to retrieve similar records, on the product-searches create form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSimilarRecordsForRequest(Request $request)
    {
        $similarRecords = ProductSearch::getSimilarRecordsForRequest($request);

        return view('product-searches.partials.similar-records', compact('similarRecords'));
    }

    public function store(ProductSearchStoreRequest $request)
    {
        ProductSearch::createMultipleRecordsFromRequest($request);

        return to_route('product-searches.index');
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function edit(Request $request, $record)
    {
        $record = ProductSearch::withTrashed()->findOrFail($record);

        return view('product-searches.edit', compact('record'));
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function update(ProductSearchUpdateRequest $request, $record)
    {
        $record = ProductSearch::withTrashed()->findOrFail($record);
        $record->updateFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    public function exportAsExcel(Request $request)
    {
        // Preapare request for valid model querying
        ProductSearch::addRefererQueryParamsToRequest($request);
        ProductSearch::addDefaultQueryParamsToRequest($request);

        // Get finalized records query
        $query = ProductSearch::withRelationsForExport();
        $filteredQuery = ProductSearch::filterQueryForRequest($query, $request);
        $records = ProductSearch::finalizeQueryForRequest($filteredQuery, $request, 'query');

        // Export records
        return ProductSearch::exportRecordsAsExcel($records);
    }
}
