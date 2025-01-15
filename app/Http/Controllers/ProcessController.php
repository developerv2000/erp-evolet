<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessUpdateRequest;
use App\Models\Process;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use App\Support\Traits\Controller\RestoresModelRecords;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    use DestroysModelRecords;
    use RestoresModelRecords;

    // used in multiple destroy/restore traits
    public static $model = Process::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Process::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Process::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Process::filterQueryForRequest($query, $request);
        $records = Process::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Add 'general_status_periods' for records
        Process::addGeneralStatusPeriodsForRecords($records);

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Process::SETTINGS_MAD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('processes.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function trash(Request $request)
    {
        // Preapare request for valid model querying
        Process::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get trashed finalized records paginated
        $query = Process::onlyTrashed()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Process::filterQueryForRequest($query, $request);
        $records = Process::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Add 'general_status_periods' for records
        Process::addGeneralStatusPeriodsForRecords($records);

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Process::SETTINGS_MAD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('processes.trash', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create()
    {
        return view('processes.create');
    }

    public function store(ProcessStoreRequest $request)
    {
        Process::createFromRequest($request);

        return to_route('processes.index');
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function edit(Request $request, $record)
    {
        $record = Process::withTrashed()->findOrFail($record);
        $record->loadBasicNonBelongsToRelations();

        return view('processes.edit', compact('record'));
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function update(ProcessUpdateRequest $request, $record)
    {
        $record = Process::withTrashed()->findOrFail($record);
        $record->updateFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    public function exportAsExcel(Request $request)
    {
        // Preapare request for valid model querying
        Process::addRefererQueryParamsToRequest($request);
        Process::addDefaultQueryParamsToRequest($request);

        // Get finalized records query
        $query = Process::withRelationsForExport();
        $filteredQuery = Process::filterQueryForRequest($query, $request);
        $records = Process::finalizeQueryForRequest($filteredQuery, $request, 'query');

        // Export records
        return Process::exportRecordsAsExcel($records);
    }
}
