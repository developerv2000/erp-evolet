<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessStoreRequest;
use App\Http\Requests\ProcessUpdateRequest;
use App\Models\Country;
use App\Models\Process;
use App\Models\ProcessStatus;
use App\Models\Product;
use App\Models\User;
use App\Support\Helpers\UrlHelper;
use App\Support\SmartFilters\MadProcessesSmartFilter;
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
        $filteredQuery = Process::addJoinsForOrdering($query, $request); // add join if joined ordering requested
        $records = Process::finalizeQueryForRequest($filteredQuery, $request, 'paginate');

        // Add 'general_status_periods' for records
        Process::addGeneralStatusPeriodsForRecords($records);

        // Get all and only visible table columns
        $allTableColumns = $request->user()->collectTableColumnsBySettingsKey(Process::SETTINGS_MAD_TABLE_COLUMNS_KEY);
        $visibleTableColumns = User::filterOnlyVisibleColumns($allTableColumns);

        return view('processes.index', compact('request', 'records', 'allTableColumns', 'visibleTableColumns'));
    }

    public function getSmartFilterDependencies()
    {
        return MadProcessesSmartFilter::getAllDependencies();
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

    public function create(Request $request)
    {
        $restrictedStatuses = ProcessStatus::getAllRestrictedByPermissions(); // IMPORTANT
        $product = Product::find($request->product_id);

        return view('processes.create', compact('product', 'restrictedStatuses'));
    }

    /**
     * Return required stage inputs, for each stage,
     * on status select change.
     *
     * Ajax request.
     */
    public function getCreateFormStageInputs(Request $request)
    {
        $product = Product::find($request->product_id);
        $status = ProcessStatus::find($request->status_id);
        $stage = $status->generalStatus->stage;

        return view('processes.partials.create-form-stage-inputs', compact('product', 'stage'));
    }

    /**
     * Return required forecast inputs, for each countries separately,
     * on status/search_countries select changes.
     *
     * Ajax request on processes.create.
     */
    public function getCreateFormForecastInputs(Request $request)
    {
        $status = ProcessStatus::find($request->status_id);
        $stage = $status->generalStatus->stage;

        $countryIDs = $request->input('country_ids', []);
        $selectedCountries = Country::whereIn('id', $countryIDs)->get();

        return view('processes.partials.create-form-forecast-inputs', compact('stage', 'selectedCountries'));
    }

    public function store(ProcessStoreRequest $request)
    {
        Process::createMultipleRecordsFromRequest($request);

        return redirect($request->input('previous_url'));
    }

    /**
     * Route model binding is not used, because trashed records can also be edited.
     * Route model binding looks only for untrashed records!
     */
    public function edit(Request $request, $record)
    {
        $record = Process::withTrashed()->findOrFail($record);
        $record->ensureAuthUserHasAccessToProcess($request);
        $product = $record->product;

        $restrictedStatuses = ProcessStatus::getAllRestrictedByPermissions(); // IMPORTANT

        return view('processes.edit', compact('record', 'product', 'restrictedStatuses'));
    }

    /**
     * Return required stage inputs, for each stage,
     * on status select change.
     *
     * Ajax request on processes.edit.
     */
    public function getEditFormStageInputs(Request $request)
    {
        $record = Process::find($request->process_id);
        $product = $record->product;

        $status = ProcessStatus::find($request->status_id);
        $stage = $status->generalStatus->stage;

        return view('processes.partials.edit-form-stage-inputs', compact('record', 'product', 'stage'));
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

    public function duplication(Request $request, Process $record)
    {
        $record->ensureAuthUserHasAccessToProcess($request);
        $product = $record->product;

        $restrictedStatuses = ProcessStatus::getAllRestrictedByPermissions(); // IMPORTANT

        return view('processes.duplicate', compact('record', 'product', 'restrictedStatuses'));
    }

    /**
     * Return required stage inputs, for each stage,
     * on status select change.
     *
     * Ajax request on processes.duplicate.
     */
    public function getDuplicateFormStageInputs(Request $request)
    {
        $record = Process::find($request->process_id);
        $product = $record->product;

        $status = ProcessStatus::find($request->status_id);
        $stage = $status->generalStatus->stage;

        return view('processes.partials.duplicate-form-stage-inputs', compact('record', 'product', 'stage'));
    }

    /**
     * Create new updated record from the specified record.
     */
    public function duplicate(Request $request)
    {
        Process::duplicateFromRequest($request);

        return redirect()->route('processes.index');
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

    /**
     * AJAX request
     */
    public function updateContractedInAspValue(Request $request)
    {
        $process = Process::find($request->process_id);
        $process->contracted_in_asp = $request->contracted_in_asp ?: false;
        $process->timestamps = false;
        $process->saveQuietly();

        return true;
    }

    /**
     * AJAX request
     */
    public function updateRegisteredInAspValue(Request $request)
    {
        $process = Process::find($request->process_id);
        $process->registered_in_asp = $request->registered_in_asp ?: false;
        $process->timestamps = false;
        $process->saveQuietly();

        return true;
    }
}
