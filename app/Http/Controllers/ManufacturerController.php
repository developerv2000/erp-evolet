<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManufacturerStoreRequest;
use App\Http\Requests\ManufacturerUpdateRequest;
use App\Models\Manufacturer;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use App\Support\Traits\Controller\RestoresModelRecords;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    use DestroysModelRecords;
    use RestoresModelRecords;

    // used in multiple destroy/restore traits
    public static $model = Manufacturer::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Manufacturer::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Manufacturer::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Manufacturer::filterQueryForRequest($query, $request);
        $records = Manufacturer::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey('MAD_EPP_table_columns');
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('manufacturers.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create()
    {
        return view('manufacturers.create');
    }

    public function store(ManufacturerStoreRequest $request)
    {
        Manufacturer::createFromRequest($request);

        return to_route('manufacturers.index');
    }

    public function edit(Manufacturer $record)
    {
        return view('manufacturers.edit', compact('record'));
    }

    public function update(ManufacturerUpdateRequest $request, Manufacturer $record)
    {
        $record->updateFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    public function exportAsExcel(Request $request)
    {
        // Preapare request for valid model querying
        Manufacturer::addRefererQueryParamsToRequest($request);
        Manufacturer::addDefaultQueryParamsToRequest($request);

        // Get finalized records query
        $query = Manufacturer::withRelationsForExport();
        $filteredQuery = Manufacturer::filterQueryForRequest($query, $request);
        $records = Manufacturer::finalizeQueryForRequest($filteredQuery, $request, 'query');

        // Export records
        return Manufacturer::exportRecordsAsExcel($records);
    }
}
