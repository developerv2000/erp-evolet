<?php

namespace App\Http\Controllers\export;

use App\Http\Controllers\Controller;
use App\Models\ProductBatch;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use Illuminate\Http\Request;

class ExportBatchController extends Controller
{
    public function index(Request $request)
    {
        // Preapare request for valid model querying
        ProductBatch::addDefaultExportQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = ProductBatch::has('assemblages')
            ->withBasicRelations()
            ->withBasicRelationCounts()
            ->with('assemblages');

        $filteredQuery = ProductBatch::filterQueryForRequest($query, $request);
        $records = ProductBatch::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(ProductBatch::SETTINGS_EXPORT_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('export.batches.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function edit(Request $request, ProductBatch $record)
    {
        return view('export.batches.edit', compact('record'));
    }

    public function update(Request $request, ProductBatch $record)
    {
        $assemblages = $request->input('assemblages', []);

        foreach ($assemblages as $assemblage) {
            $record->assemblages()->updateExistingPivot($assemblage['id'], [
                'quantity_for_assembly' => $assemblage['quantity_for_assembly'],
                'additional_comment' => $assemblage['additional_comment'],
            ]);
        }

        return redirect($request->input('previous_url'));
    }
}
