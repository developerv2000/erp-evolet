<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeetingStoreRequest;
use App\Http\Requests\MeetingUpdateRequest;
use App\Models\Meeting;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use App\Support\Traits\Controller\RestoresModelRecords;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    use DestroysModelRecords;
    use RestoresModelRecords;

    // used in multiple destroy/restore traits
    public static $model = Meeting::class;

    public function index(Request $request)
    {
        // Preapare request for valid model querying
        Meeting::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get finalized records paginated
        $query = Meeting::withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Meeting::filterQueryForRequest($query, $request);
        $records = Meeting::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Meeting::SETTINGS_MAD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('meetings.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function trash(Request $request)
    {
        // Preapare request for valid model querying
        Meeting::addDefaultQueryParamsToRequest($request);
        UrlHelper::addUrlWithReversedOrderTypeToRequest($request);

        // Get trashed finalized records paginated
        $query = Meeting::onlyTrashed()->withBasicRelations()->withBasicRelationCounts();
        $filteredQuery = Meeting::filterQueryForRequest($query, $request);
        $records = Meeting::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Meeting::SETTINGS_MAD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('meetings.trash', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function create()
    {
        return view('meetings.create');
    }

    public function store(MeetingStoreRequest $request)
    {
        Meeting::createFromRequest($request);

        return to_route('meetings.index');
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function edit(Request $request, $record)
    {
        $record = Meeting::withTrashed()->findOrFail($record);

        return view('meetings.edit', compact('record'));
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function update(MeetingUpdateRequest $request, $record)
    {
        $record = Meeting::withTrashed()->findOrFail($record);
        $record->updateFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    public function exportAsExcel(Request $request)
    {
        // Preapare request for valid model querying
        Meeting::addRefererQueryParamsToRequest($request);
        Meeting::addDefaultQueryParamsToRequest($request);

        // Get finalized records query
        $query = Meeting::withRelationsForExport();
        $filteredQuery = Meeting::filterQueryForRequest($query, $request);
        $records = Meeting::finalizeQueryForRequest($filteredQuery, $request, 'query');

        // Export records
        return Meeting::exportRecordsAsExcel($records);
    }
}
