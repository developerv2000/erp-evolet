<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Manufacturer;
use App\Models\Process;
use App\Models\ProcessGeneralStatus;
use App\Support\Helpers\GeneralHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MADKPIController extends Controller
{
    /**
     * There are two types of KPI versions: MINIFIED and EXTENSIVE.
     * On MINIFIED version only first 5 stages of general statuses are displayed.
     * On EXTENSIVE version all stages of general statuses are displayed.
     *
     * IMPORTANT: Special queries and links used for stages 5(Кк) and 7(НПР).
     * No differences for for stages 5(Кк) and 7(НПР) between MINIFIED and EXTENSIVE versions.
     */
    public function index(Request $request)
    {
        // Preapare request for valid querying
        $this->mergeDefaultParamsToRequest($request);

        // Get requested months
        $months = $this->getMonthsFromRequest($request);

        // Get general statuses for EXTENSIVE or MINIFIED versions of KPI and initialize them
        $generalStatuses = $this->getGeneralStatusesFromRequest($request);
        $this->initializeGeneralStatuses($generalStatuses, $months);

        // Add current process counts and links
        $this->addCurrentProcessCountsForStatusMonths($generalStatuses, $months, $request);
        $this->addCurrentProcessLinksForStatusMonths($generalStatuses, $request);

        // Add maximum process counts and links
        $this->addMaximumProcessCountsForStatusMonths($generalStatuses, $months, $request);
        $this->addMaximumProcessLinksForStatusMonths($generalStatuses, $request);

        // Calculate 'sum_of_monthly_processes' of general statuses
        $this->addSumOfMonthlyProcessesForStatuses($generalStatuses);
        // Calculate 'sum_of_all_status_processes' of months
        $this->addSumOfAllStatusesForMonths($generalStatuses, $months);

        // Calculate 'active manufacturers' of months and add links
        $this->addActiveManufacturersCountsForMonths($months, $request);
        $this->addActiveManufacturersLinksForMonths($months, $request);

        // Calculate yearly counts
        $yearlyCurrentProcesses = $generalStatuses->sum('sum_of_monthly_current_processes');
        $yearlyMaximumProcesses = $generalStatuses->sum('sum_of_monthly_maximum_processes');
        $yearlyActiveManufacturers = $months->sum('active_manufacturers_count');

        // Get all countries which has processes with processes count for each general statuses
        $countriesWhichHasProcesses = $this->getCountriesWhichHasProcessesFromRequest($request);
        $countries = $this->addCurrentProcessCountsForCountries($countriesWhichHasProcesses, $generalStatuses, $months, $request);

        // Compact all in single variable
        $kpi = [
            'months' => array_values($months->toArray()), // Important: convert into array to avoid JS errors
            'generalStatuses' => $generalStatuses,
            'yearlyCurrentProcesses' => $yearlyCurrentProcesses,
            'yearlyMaximumProcesses' => $yearlyMaximumProcesses,
            'yearlyActiveManufacturers' => $yearlyActiveManufacturers,
            'countries' => array_values($countries->toArray()),
        ];

        return view('mad-kpi.index', compact('request', 'kpi'));
    }

    /*
    |-------------------------------------------------------
    | General helpers
    |-------------------------------------------------------
    */

    /**
     * Merge default parameters to the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function mergeDefaultParamsToRequest($request)
    {
        // Merge year
        $request->mergeIfMissing([
            'year' => date('Y'),
        ]);

        // Restrict non-priviliged analysts to only see their own statistics
        $user = $request->user();

        if (Gate::denies('view-MAD-KPI-of-all-analysts') && $user->isMADAnalyst()) {
            $request->merge([
                'analyst_user_id' => $user->id,
            ]);
        }
    }

    /**
     * Get translated months based on the request parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    private function getMonthsFromRequest($request)
    {
        // Define the array of months
        $months = GeneralHelper::collectCalendarMonths();

        // Get only request months
        if ($request->input('months')) {
            $months = $months->whereIn('name', $request->input('months'))
                ->sortBy('number');
        }

        // Translate months
        GeneralHelper::translateMonthNames($months);

        return $months;
    }

    /**
     * Get filtered general statuses for EXTENSIVE or MINIFIED versions of KPI,
     * based on request parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getGeneralStatusesFromRequest($request)
    {
        // Query to retrieve general statuses
        $query = ProcessGeneralStatus::query();

        // Apply filtering based on request parameters
        $query->when(!$request->extensive_version, function ($statuses) {
            $statuses->where('stage', '<=', 5);
        });

        // Order the statuses by stage in ascending order
        $query->orderBy('stage', 'asc');

        // Retrieve and return the filtered general statuses
        return $query->get();
    }

    /**
     * Initialize general statuses by adding required attributes with initial values
     * to avoid errors and duplications.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $generalStatuses
     * @param  \Illuminate\Support\Collection  $months
     * @return void
     */
    private static function initializeGeneralStatuses($generalStatuses, $months)
    {
        $defaultMonthData = fn($monthNumber) => [
            'number' => $monthNumber,
            'current_processes_count' => 0,
            'maximum_processes_count' => 0,
            'current_processes_link' => '#',
            'maximum_processes_link' => '#',
        ];

        foreach ($generalStatuses as $status) {
            // Prepare the months array with default values
            $status->months = $months->pluck('number')->mapWithKeys(fn($month) => [
                $month => $defaultMonthData($month)
            ])->toArray();

            // Add sum of monthly processes
            $status->sum_of_monthly_current_processes = 0;
            $status->sum_of_monthly_maximum_processes = 0;
        }
    }

    /**
     * Get only required filter query parameters from the request for Process model.
     *
     * @param Illuminate\Http\Request $request
     * @return array
     */
    private function getFilterQueryParametersForProcesses($request)
    {
        return $request->only([
            'analyst_user_id',
            'bdm_user_id',
            'country_id',
            'region',
        ]);
    }

    /**
     * Get only required filter query parameters from the request for Process model.
     *
     * @param Illuminate\Http\Request $request
     * @return array
     */
    private function getFilterQueryParametersForManufacturers($request)
    {
        $query = $request->only([
            'analyst_user_id',
            'bdm_user_id',
            'region',
        ]);

        if ($request->has('country_id')) {
            $query['process_country_id'] = $request->country_id;
        }

        return $query;
    }

    /**
     * Generate month range in format of current_month_d/m/y - next_month_d/m/y for date.
     *
     * @param int $year
     * @param int $month
     * @return string
     */
    private static function generateMonthRangeForDate($year, $month)
    {
        $monthStart = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-01');
        $nextMonthStart = $monthStart->copy()->addMonth()->startOfMonth();

        return $monthStart->format('d/m/Y') . ' - ' . $nextMonthStart->format('d/m/Y');
    }

    /*
    |-------------------------------------------------------
    | Current process calculations & adding links
    |-------------------------------------------------------
    */

    /**
     * Add current process counts for each months of general statuses.
     *
     * Iterates through general statuses and months to add 'current_processes_count' for each month of statuses.
     * Special queries are used for stages 5(Кк) and 7(НПР).
     */
    private function addCurrentProcessCountsForStatusMonths($generalStatuses, $months, $request)
    {
        foreach ($generalStatuses as $status) {
            foreach ($months as $month) {
                $query = Process::query();
                $clonedRequest = $request->duplicate();

                // Determine query modifications based on status stage
                if ($status->stage == 5) { // Special query for stage 5(Kk)
                    $clonedRequest->merge([
                        'contracted_on_specific_month' => true,
                        'contracted_on_year' => $request->year,
                        'contracted_on_month' => $month['number'],
                    ]);
                } elseif ($status->stage == 7) { // Special query for stage 7(НПР)
                    $clonedRequest->merge([
                        'registered_on_specific_month' => true,
                        'registered_on_year' => $request->year,
                        'registered_on_month' => $month['number'],
                    ]);
                } else {
                    $query->whereHas('activeStatusHistory', function ($historyQuery) use ($status, $month, $clonedRequest) {
                        $historyQuery->whereYear('start_date', $clonedRequest->year)
                            ->whereMonth('start_date', $month['number'])
                            ->whereHas('status.generalStatus', fn($statusesQuery) => $statusesQuery->where('id', $status->id));
                    });
                }

                // Apply base filters
                $query = Process::filterQueryForRequest($query, $clonedRequest, applyPermissionsFilter: false);

                // Get current processes count of the month for the status
                $processesCount = $query->count();

                // Update the current processes count of the month for the status
                $statusMonths = $status->months;
                $statusMonths[$month['number']]['current_processes_count'] = $processesCount;
                $status->months = $statusMonths;
            }
        }
    }

    /**
     * Add current process links for each months of general statuses.
     *
     * Special links for stages 5(Кк) and 7(НПР).
     *
     * @param \Illuminate\Http\Request $request
     * @param array $generalStatuses
     * @return void
     */
    private function addCurrentProcessLinksForStatusMonths($generalStatuses, $request)
    {
        // Get required filter query parameters from request
        $queryParams = $this->getFilterQueryParametersForProcesses($request);

        foreach ($generalStatuses as $status) {
            foreach ($status->months as $month) {
                $queryParamsCopy = $queryParams;

                // Special query for stage 5(Kk)
                if ($status->stage == 5) {
                    $queryParamsCopy['contracted_on_specific_month'] = true;
                    $queryParamsCopy['contracted_on_year'] = $request->year;
                    $queryParamsCopy['contracted_on_month'] = $month['number'];

                    // Special query for stage 7(НПР)
                } else if ($status->stage == 7) {
                    $queryParamsCopy['registered_on_specific_month'] = true;
                    $queryParamsCopy['registered_on_year'] = $request->year;
                    $queryParamsCopy['registered_on_month'] = $month['number'];

                    // Default query
                } else {
                    $queryParamsCopy['general_status_id[]'] = $status->id;
                    $queryParamsCopy['active_status_start_date_range'] = $this->generateMonthRangeForDate($request->year, $month['number']);
                }

                $link = route('processes.index', $queryParamsCopy);

                // Update the current processes link of the month for the status
                $statusMonths = $status->months;
                $statusMonths[$month['number']]['current_processes_link'] = $link;
                $status->months = $statusMonths;
            }
        }
    }

    /*
    |-------------------------------------------------------
    | Maximum process calculations & adding links
    |-------------------------------------------------------
    */

    /**
     * Add maximum process counts for each months of general statuses.
     *
     * Iterates through general statuses and months to add 'maximum_processes_count' for each month of statuses.
     * Special queries are used for stages 5(Кк) and 7(НПР).
     */
    private function addMaximumProcessCountsForStatusMonths($generalStatuses, $months, $request)
    {
        foreach ($generalStatuses as $status) {
            foreach ($months as $month) {
                $query = Process::query();
                $clonedRequest = $request->duplicate();

                // Determine query modifications based on status stage
                if ($status->stage == 5) { // Special query for stage 5(Kk)
                    $clonedRequest->merge([
                        'contracted_on_specific_month' => true,
                        'contracted_on_year' => $request->year,
                        'contracted_on_month' => $month['number'],
                    ]);
                } elseif ($status->stage == 7) { // Special query for stage 7(НПР)
                    $clonedRequest->merge([
                        'registered_on_specific_month' => true,
                        'registered_on_year' => $request->year,
                        'registered_on_month' => $month['number'],
                    ]);
                } else {
                    $clonedRequest->merge([
                        'has_general_status_history' => true,
                        'has_general_status_for_year' => $request->year,
                        'has_general_status_for_month' => $month['number'],
                        'has_general_status_id' => $status->id,
                    ]);
                }

                // Apply base filters
                $query = Process::filterQueryForRequest($query, $clonedRequest, applyPermissionsFilter: false);

                // Get maxmimum processes count of the month for the status
                $processesCount = $query->count();

                // Update the maxmimum processes count of the month for the status
                $statusMonths = $status->months;
                $statusMonths[$month['number']]['maximum_processes_count'] = $processesCount;
                $status->months = $statusMonths;
            }
        }
    }

    /**
     * Add maximum process links for each months of general statuses.
     *
     * Special links for stages 5(Кк) and 7(НПР).
     *
     * @param \Illuminate\Http\Request $request
     * @param array $generalStatuses
     * @return void
     */
    private function addMaximumProcessLinksForStatusMonths($generalStatuses, $request)
    {
        // Get required filter query parameters from request
        $queryParams = $this->getFilterQueryParametersForProcesses($request);

        foreach ($generalStatuses as $status) {
            foreach ($status->months as $month) {
                $queryParamsCopy = $queryParams;

                // Special query for stage 5(Kk)
                if ($status->stage == 5) {
                    $queryParamsCopy['contracted_on_specific_month'] = true;
                    $queryParamsCopy['contracted_on_year'] = $request->year;
                    $queryParamsCopy['contracted_on_month'] = $month['number'];

                    // Special query for stage 7(НПР)
                } else if ($status->stage == 7) {
                    $queryParamsCopy['registered_on_specific_month'] = true;
                    $queryParamsCopy['registered_on_year'] = $request->year;
                    $queryParamsCopy['registered_on_month'] = $month['number'];

                    // Default query
                } else {
                    $queryParamsCopy['has_general_status_history'] = true;
                    $queryParamsCopy['has_general_status_for_year'] = $request->year;
                    $queryParamsCopy['has_general_status_for_month'] = $month['number'];
                    $queryParamsCopy['has_general_status_id'] = $status->id;
                }

                $link = route('processes.index', $queryParamsCopy);

                // Update the maximum processes link of the month for the status
                $statusMonths = $status->months;
                $statusMonths[$month['number']]['maximum_processes_link'] = $link;
                $status->months = $statusMonths;
            }
        }
    }

    /*
    |--------------------------------------------------------
    | Adding 'sum_of_monthly_processes' for general statuses
    |--------------------------------------------------------
    */

    private function addSumOfMonthlyProcessesForStatuses($generalStatuses)
    {
        foreach ($generalStatuses as $status) {
            $sumOfCurrentProcesses = 0;
            $sumOfMaximumProcesses = 0;

            foreach ($status->months as $month) {
                $sumOfCurrentProcesses += $month['current_processes_count'];
                $sumOfMaximumProcesses += $month['maximum_processes_count'];
            }

            $status->sum_of_monthly_current_processes = $sumOfCurrentProcesses;
            $status->sum_of_monthly_maximum_processes = $sumOfMaximumProcesses;
        }
    }

    /*
    |-----------------------------------------
    | Adding 'sum_of_all_statuses' for months
    |-----------------------------------------
    */

    private function addSumOfAllStatusesForMonths($generalStatuses, $months)
    {
        foreach ($months as $month) {
            $sumOfCurrentProcesses = 0;
            $sumOfMaximumProcesses = 0;

            foreach ($generalStatuses as $status) {
                $sumOfCurrentProcesses += $status->months[$month['number']]['current_processes_count'];
                $sumOfMaximumProcesses += $status->months[$month['number']]['maximum_processes_count'];
            }

            $month['sum_of_all_current_process'] = $sumOfCurrentProcesses;
            $month['sum_of_all_maximum_process'] = $sumOfMaximumProcesses;
        }
    }

    /*
    |-----------------------------------------
    | Active manufacturers counts & links
    |-----------------------------------------
    */

    private function addActiveManufacturersCountsForMonths($months, $request)
    {
        foreach ($months as $month) {
            $query = Manufacturer::query();
            $clonedRequest = $request->duplicate();

            // Validate 'country_code' from request
            if ($clonedRequest->has('country_id')) {
                $clonedRequest->merge([
                    'process_country_id' => $clonedRequest->country_id,
                    'country_id' => null,
                ]);
            }

            // Add required parameters for querying active manufacturers
            $clonedRequest->merge([
                'has_active_processes_for_specific_month' => true,
                'has_active_processes_for_year' => $request->year,
                'has_active_processes_for_month' => $month['number'],
            ]);

            // Apply base filters
            $query = Manufacturer::filterQueryForRequest($query, $clonedRequest);

            // Set active manufacturers for month
            $month['active_manufacturers_count'] = $query->count();;
        }
    }

    private function addActiveManufacturersLinksForMonths($months, $request)
    {
        // Get required filter query parameters from request
        $queryParams = $this->getFilterQueryParametersForManufacturers($request);

        foreach ($months as $month) {
            $queryParamsCopy = $queryParams;

            $queryParamsCopy['has_active_processes_for_specific_month'] = true;
            $queryParamsCopy['has_active_processes_for_year'] = $request->year;
            $queryParamsCopy['has_active_processes_for_month'] = $month['number'];

            $link = route('manufacturers.index', $queryParamsCopy);
            $month['active_manufacturers_link'] = $link;
        }
    }

    /*
    |-----------------------------------------
    | Country processes count
    |-----------------------------------------
    */

    private function getCountriesWhichHasProcessesFromRequest($request)
    {
        $query = Country::whereHas('processes');

        if ($request->filled('country_id')) {
            $query->whereIn('id', $request->input('country_id'));
        }

        return $query->select('id', 'name', 'code')->get();
    }

    private function addCurrentProcessCountsForCountries($countries, $generalStatuses, $months, $request)
    {
        // Prepare Process query for request
        $query = Process::query();

        // Apply base filters
        $query = Process::filterQueryForRequest($query, $request, applyPermissionsFilter: false);

        // Pluck month numbers for filtering
        $monthNumbers = $months->pluck('number');

        foreach ($countries as $country) {
            // Calculate total process count
            $clonedQuery = $query->clone();

            $clonedQuery->where('country_id', $country->id)
                ->whereHas('activeStatusHistory', function ($historyQuery) use ($monthNumbers, $request) {
                    $historyQuery->whereYear('start_date', $request->year)
                        ->whereIn(DB::raw('MONTH(start_date)'), $monthNumbers);
                });

            $totalProcesses = $clonedQuery->count();
            $country->value = $totalProcesses; // Set total process count as 'value' attribute for echarts

            if (!$totalProcesses) {
                // Skip general status processes count
                continue;
            } else {
                // Else create 'statuses' attribute
                $country->statuses = [];
            }

            // Calculate each general status processes count separately
            foreach ($generalStatuses as $status) {
                $clonedQuery = $query->clone();

                $clonedQuery->where('country_id', $country->id)
                    ->whereHas('activeStatusHistory', function ($historyQuery) use ($status, $monthNumbers, $request) {
                        $historyQuery->whereYear('start_date', $request->year)
                            ->whereIn(DB::raw('MONTH(start_date)'), $monthNumbers)
                            ->whereHas('status.generalStatus', fn($statusesQuery) => $statusesQuery->where('id', $status->id));
                    });

                $statuses = $country->statuses;
                $statuses[$status->name] = $clonedQuery->count(); // set processes_count as 'status_name' for echarts
                $country->statuses = $statuses;
            }
        }

        // Filter only countries with processes
        return $countries->where('value', '>', 0);
    }
}
