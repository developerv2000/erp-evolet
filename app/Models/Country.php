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
    use ScopesOrderingByUsageCount;
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

    public static function getRegionOptions(): array
    {
        return [
            'Europe',
            'India',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    // Implement method declared in UsageCountable interface
    public function recalculateUsageCount(): void
    {
        // $this->update([
        //     'usage_count' => $this->processes()->count() + $this->productSearches()->count(),
        // ]);
    }
}
