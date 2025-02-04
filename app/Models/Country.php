<?php

namespace App\Models;

use App\Support\Contracts\Model\UsageCountable;
use App\Support\Helpers\GeneralHelper;
use App\Support\Traits\Model\CalculatesAspQuarterAndYearCounts;
use App\Support\Traits\Model\RecalculatesAllUsageCounts;
use App\Support\Traits\Model\ScopesOrderingByName;
use App\Support\Traits\Model\ScopesOrderingByUsageCount;
use Illuminate\Database\Eloquent\Model;

class Country extends Model implements UsageCountable
{
    use ScopesOrderingByName;
    use RecalculatesAllUsageCounts;
    use CalculatesAspQuarterAndYearCounts;

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

    public function manufacturers()
    {
        return $this->hasMany(Manufacturer::class);
    }

    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    public function responsibleUsers()
    {
        return $this->belongsToMany(User::class, 'responsible_country_user');
    }

    public function clinicalTrialProcesses()
    {
        return $this->belongsToMany(Process::class, 'clinical_trial_country_process');
    }

    public function productSearches()
    {
        return $this->hasMany(ProductSearch::class);
    }

    public function additionalProductSearches()
    {
        return $this->belongsToMany(ProductSearch::class, 'additional_search_country_product_search');
    }

    public function madAsps()
    {
        return $this->belongsToMany(MadAsp::class, 'mad_asp_country_marketing_authorization_holder');
    }

    public function madAspMAHs()
    {
        return $this->belongsToMany(MarketingAuthorizationHolder::class, 'mad_asp_country_marketing_authorization_holder')
            ->withPivot(MadAsp::getPivotColumnNamesForMAHRelation());
    }

    /**
     * Return marketing authorization holders for specific MAD ASP
     */
    public function MAHsOfSpecificMadAsp($asp)
    {
        return $this->madAspMAHs()
            ->wherePivot('mad_asp_id', $asp->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeOrderByProcessesCount($query)
    {
        return $query->orderBy('database_processes_count', 'desc');
    }

    /*
    |--------------------------------------------------------------------------
    | Queries
    |--------------------------------------------------------------------------
    */

    /**
     * Used on filters
     */
    public static function getIndiaCountryID(): int
    {
        return self::where('name', 'India')->value('id');
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    // Implement method declared in UsageCountable interface.
    public static function recalculateAllUsageCounts(): void {}

    // Implement method declared in UsageCountable interface.
    public function recalculateUsageCount(): void
    {
        // $this->update([
        //     'usage_count' => $this->processes_count + $this->product_searches_count,
        // ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public static function getRegionOptions(): array
    {
        return [
            'Europe',
            'India',
        ];
    }

    public static function recalculateAllProcessCounts()
    {
        $records = self::withCount('processes')->get();

        foreach ($records as $record) {
            $record->database_processes_count = $record->processes_count;
            $record->save();
        }
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
        $this->calculateAspMonthlyProcessCounts();

        // Step 2: Calculate quarterly process counts from monthly data (CalculatesAspQuarterAndYearCounts trait)
        $this->calculateAspQuartersProcessCounts();

        // Step 3: Calculate yearly process counts from monthly and quarterly data (CalculatesAspQuarterAndYearCounts trait)
        $this->calculateAspYearProcessCounts();

        // Step 4: Calculate percentages for yearly process counts (e.g., success rates) (CalculatesAspQuarterAndYearCounts trait)
        $this->calculateAspYearPercentages();
    }

    private function calculateAspMonthlyProcessCounts()
    {
        $months = GeneralHelper::collectCalendarMonths();

        foreach ($months as $month) {
            $monthName = $month['name'];

            // Calculate the totals for the current month
            [$contractPlanCount, $contractFactCount, $registerFactCount] = $this->sumMonthlyCountsOfMAHs($monthName);

            // Assign totals to the current model instance
            $this->{$monthName . '_contract_plan'} = $contractPlanCount;
            $this->{$monthName . '_contract_fact'} = $contractFactCount;
            $this->{$monthName . '_register_fact'} = $registerFactCount;
        }
    }

    /**
     * Sum the 'contract_plan', 'contract_fact', and 'register_fact' counts for a specific month
     * across all related Marketing Authorization Holders (MAHs).
     *
     * @param  string  $monthName
     * @return array  [contractPlanCount, contractFactCount, registerFactCount]
     */
    private function sumMonthlyCountsOfMAHs($monthName)
    {
        $contractPlanCount = 0;
        $contractFactCount = 0;
        $registerFactCount = 0;

        // Iterate through all marketing authorization holders
        foreach ($this->MAHs as $mah) {
            $contractPlanCount += $mah->{$monthName . '_contract_plan'} ?? 0;
            $contractFactCount += $mah->{$monthName . '_contract_fact'} ?? 0;
            $registerFactCount += $mah->{$monthName . '_register_fact'} ?? 0;
        }

        return [$contractPlanCount, $contractFactCount, $registerFactCount];
    }
}
