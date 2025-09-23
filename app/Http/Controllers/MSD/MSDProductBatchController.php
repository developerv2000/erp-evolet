<?php

namespace App\Http\Controllers\MSD;

use App\Http\Controllers\Controller;
use App\Models\ProductBatch;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class MSDProductBatchController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        ProductBatch::addDefaultMSDQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = ProductBatch::onlySerializedByUs()
            ->withBasicRelations()
            ->withBasicRelationCounts();

        $filteredQuery = ProductBatch::filterQueryForRequest($query, $request);
        $records = ProductBatch::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(ProductBatch::SETTINGS_MSD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('MSD.product-batches.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function edit(Request $request, ProductBatch $record)
    {
        return view('MSD.product-batches.edit', compact('record'));
    }

    public function update(Request $request, ProductBatch $record)
    {
        $record->updateByMSDFromRequest($request);

        return redirect($request->input('previous_url'));
    }
}
