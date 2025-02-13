<?php

namespace App\Models;

use App\Support\Contracts\Model\TracksUsageCount;
use App\Support\Helpers\GeneralHelper;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\CalculatesAspQuarterAndYearCounts;
use App\Support\Traits\Model\PreventsDeletionIfInUse;
use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MarketingAuthorizationHolder extends Model implements TracksUsageCount
{
    use ScopesOrderingByName;
    use CalculatesAspQuarterAndYearCounts;
    use PreventsDeletionIfInUse;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const UNDER_DISCUSSION_SHORT_NAME = 'Обс.'; // used in MAD ASP show page

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public $timestamps = false;
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    public function productSearches()
    {
        return $this->hasMany(ProductSearch::class);
    }

    public function madAsps()
    {
        return $this->belongsToMany(MadAsp::class, 'mad_asp_country_marketing_authorization_holder');
    }

    public function madAspCountries()
    {
        return $this->belongsToMany(Country::class, 'mad_asp_country_marketing_authorization_holder')
            ->withPivot(MadAsp::getPivotColumnNamesForMAHRelation());
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    //Implement method declared in 'TracksUsageCount' interface.
    public function scopeWithRelatedUsageCounts($query)
    {
        return $query->withCount([
            'processes',
            'productSearches',
            'madAsps',
        ]);
    }

    //Implement method declared in 'TracksUsageCount' interface.
    public function getUsageCountAttribute()
    {
        return $this->processes_count +
            $this->product_searches_count;
            $this->mad_asps_count;
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public static function getDefaultSelectedIDValue()
    {
        return self::where('name', 'Обсуждается')->value('id');
    }

    /**
     * Used on MAD ASP show page.
     */
    public static function getUnderDiscussionNamedRecord()
    {
        return self::where('name', 'Обсуждается')->first();
    }

    /*
    |--------------------------------------------------------------------------
    | MAD ASP calculations
    |--------------------------------------------------------------------------
    */

    /**
     * Perform all necessary MAD ASP calculations.
     *
     * @param MadAsp $asp The $asp model associated with the MAH
     * @param \Illuminate\Http\Request $request The request object containing user inputs
     */
    public function makeAllMadAspCalculations($asp, $request)
    {
        // Step 1: Prepare contract plan calculations based on the provided request and plan
        $this->prepareForMadAspCalculations($request);

        // Step 2: Calculate monthly process counts
        $this->calculateMadAspMonthlyProcessCounts($asp, $request);

        // Step 3: Add monthly process count links
        $this->addMadAspMonthlyProcessCountLinks($asp, $request);

        // Step 4: Calculate quarterly process counts from monthly data (CalculatesAspQuarterAndYearCounts trait)
        $this->calculateAspQuartersProcessCounts();

        // Step 5: Calculate yearly process counts from monthly and quarterly data (CalculatesAspQuarterAndYearCounts trait)
        $this->calculateAspYearProcessCounts();

        // Step 6: Calculate percentages for yearly process counts (e.g., success rates) (CalculatesAspQuarterAndYearCounts trait)
        $this->calculateAspYearPercentages();
    }

    /**
     * Prepare MAH for MAD ASP calculations,
     * based on the specified region (if any) and the MAH`s pivot data.
     *
     * Adds below properties to the current MAH, For each month of the year:
     * $month['name'] . '_contract_plan'
     *
     * @param Request $request The request object
     */
    public function prepareForMadAspCalculations($request)
    {
        $region = $request->input('region');
        $months = GeneralHelper::collectCalendarMonths();

        // Determine which contract plans to calculate based on the manufacturer country
        switch ($region) {
            case null:
                // If no specific region is provided:
                // Sum both Europe and India contract plans for each month
                foreach ($months as $month) {
                    $monthName = $month['name'];
                    $this->{$monthName . '_contract_plan'} = $this->pivot->{$monthName . '_europe_contract_plan'}
                        + $this->pivot->{$monthName . '_india_contract_plan'};
                }
                break;

            case 'Europe':
                // If region is Europe:
                // Set only the Europe contract plans for each month
                foreach ($months as $month) {
                    $monthName = $month['name'];
                    $this->{$monthName . '_contract_plan'} = $this->pivot->{$monthName . '_europe_contract_plan'};
                }
                break;

            case 'India':
                // If region is India:
                // Set only the India contract plans for each month
                foreach ($months as $month) {
                    $monthName = $month['name'];
                    $this->{$monthName . '_contract_plan'} = $this->pivot->{$monthName . '_india_contract_plan'};
                }
                break;
        }
    }

    /**
     * Calculate MAH`s contracted and registered processes count for each month of the year.
     *
     * Adds below properties to the current MAH, For each month of the year:
     * $month['name'] . '_contract_fact'
     * $month['name'] . '_register_fact'
     */
    public function calculateMadAspMonthlyProcessCounts($asp, $request)
    {
        // Prepare the base request params for filtering records
        $baseRequestParams = [
            'marketing_authorization_holder_id' => [$this->id],
            'country_id' => [$this->pivot->country_id],
            'region' => $request->input('region'),
        ];

        $baseContractedRequestParams = [
            ...$baseRequestParams,
            'contracted_in_asp' => true,
            'contracted_on_specific_month' => true,
        ];

        $baseRegisteredRequestParams = [
            ...$baseRequestParams,
            'registered_in_asp' => true,
            'registered_on_specific_month' => true,
        ];

        // Loop through each month and calculate the processes counts
        $months = GeneralHelper::collectCalendarMonths();

        foreach ($months as $month) {
            // 1. Contract Fact
            // Prepare the base query for filtering records
            $contractedRequest = new Request([
                ...$baseContractedRequestParams,
                'contracted_on_year' => $asp->year,
                'contracted_on_month' => $month['number']
            ]);

            // 2. Register Fact
            // Prepare the base query for filtering records
            $registeredRequest = new Request([
                ...$baseRegisteredRequestParams,
                'registered_on_year' => $asp->year,
                'registered_on_month' => $month['number']
            ]);

            // Apply filtering and count
            $contractedQuery = Process::filterQueryForRequest(Process::query(), $contractedRequest, applyPermissionsFilter: false);
            $this->{$month['name'] . '_contract_fact'} = $contractedQuery->count();

            $registeredQuery = Process::filterQueryForRequest(Process::query(), $registeredRequest, applyPermissionsFilter: false);;
            $this->{$month['name'] . '_register_fact'} = $registeredQuery->count();
        }
    }

    /**
     * Generate process count links (processes.index) for each month and store them in the model.
     *
     * This method sets the following properties for each month:
     * - 'month_contract_fact_link'
     * - 'month_register_fact_link'
     *
     * @param MadAsp $asp The plan object
     * @param \Illuminate\Http\Request $request The request object
     * @return void
     */
    public function addMadAspMonthlyProcessCountLinks($asp, $request)
    {
        // Build the base query parameters for generating process links.
        $baseQueryParams = [
            'marketing_authorization_holder_id[]' => $this->id,
            'country_id[]' => $this->pivot->country_id,
            'region' => $request->input('region'),
        ];

        // Loop through each month and generate links
        $months = GeneralHelper::collectCalendarMonths();

        foreach ($months as $month) {
            $monthNumber = $month['number'];

            // Generate contracted processes link and assign it to the model
            $contractedParams = array_merge($baseQueryParams, [
                'contracted_in_asp' => true,
                'contracted_on_specific_month' => true,
                'contracted_on_year' => $asp->year,
                'contracted_on_month' => $monthNumber,
            ]);
            $contractedProcessesLink = route('processes.index', $contractedParams);
            $this->{$month['name'] . '_contract_fact_link'} = $contractedProcessesLink;

            // Generate registered processes link and assign it to the model
            $registeredParams = array_merge($baseQueryParams, [
                'registered_in_asp' => true,
                'registered_on_specific_month' => true,
                'registered_on_year' => $asp->year,
                'registered_on_month' => $monthNumber,
            ]);
            $registeredProcessesLink = route('processes.index', $registeredParams);
            $this->{$month['name'] . '_register_fact_link'} = $registeredProcessesLink;
        }
    }
}
