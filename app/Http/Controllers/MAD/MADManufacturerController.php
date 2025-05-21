<?php

namespace App\Http\Controllers\MAD;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManufacturerStoreRequest;
use App\Http\Requests\ManufacturerUpdateRequest;
use App\Models\Manufacturer;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\SmartFilters\MAD\MADManufacturersSmartFilter;
use App\Support\Traits\Controller\DestroysModelRecords;
use App\Support\Traits\Controller\RestoresModelRecords;
use Illuminate\Http\Request;

class MADManufacturerController extends Controller
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
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Manufacturer::SETTINGS_MAD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('MAD.manufacturers.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function getSmartFilterDependencies()
    {
        return MADManufacturersSmartFilter::getAllDependencies();
    }

    public function trash(Request $request)
    {
        // Preapare request for valid model querying
        Manufacturer::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get trashed finalized records paginated
        $query = Manufacturer::onlyTrashed()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Manufacturer::filterQueryForRequest($query, $request);
        $records = Manufacturer::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Manufacturer::SETTINGS_MAD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('MAD.manufacturers.trash', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create()
    {
        return view('MAD.manufacturers.create');
    }

    public function store(ManufacturerStoreRequest $request)
    {
        Manufacturer::createFromRequest($request);

        return to_route('mad.manufacturers.index');
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function edit(Request $request, $record)
    {
        $record = Manufacturer::withTrashed()->findOrFail($record);

        return view('MAD.manufacturers.edit', compact('record'));
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function update(ManufacturerUpdateRequest $request, $record)
    {
        $record = Manufacturer::withTrashed()->findOrFail($record);
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
