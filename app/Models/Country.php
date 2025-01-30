<?php

namespace App\Models;

use App\Support\Contracts\Model\UsageCountable;
use App\Support\Traits\Model\RecalculatesAllUsageCounts;
use App\Support\Traits\Model\ScopesOrderingByName;
use App\Support\Traits\Model\ScopesOrderingByUsageCount;
use Illuminate\Database\Eloquent\Model;

class Country extends Model implements UsageCountable
{
    use ScopesOrderingByName;
    use RecalculatesAllUsageCounts;

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
}
