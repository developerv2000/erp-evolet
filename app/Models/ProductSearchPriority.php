<?php

namespace App\Models;

use App\Support\Contracts\Model\TracksUsageCount;
use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Model;

class ProductSearchPriority extends Model implements TracksUsageCount
{
    use ScopesOrderingByName;

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

    public function productSearches()
    {
        return $this->hasMany(ProductSearch::class, 'priority_id');
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
            'productSearches',
        ]);
    }

    //Implement method declared in 'TracksUsageCount' interface.
    public function getUsageCountAttribute()
    {
        return $this->product_searches_count;
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public static function getDefaultSelectedIDValue()
    {
        return self::where('name', 'B')->value('id');
    }
}
