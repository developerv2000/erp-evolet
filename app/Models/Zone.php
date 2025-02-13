<?php

namespace App\Models;

use App\Support\Contracts\Model\TracksUsageCount;
use App\Support\Traits\Model\FindsRecordByName;
use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model implements TracksUsageCount
{
    use ScopesOrderingByName;
    use FindsRecordByName;

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
        return $this->belongsToMany(Manufacturer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
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
            'manufacturers',
            'products',
        ]);
    }

    //Implement method declared in 'TracksUsageCount' interface.
    public function getUsageCountAttribute()
    {
        return $this->manufacturers_count +
            $this->products_count;
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    // Get default selected id values on related-models.create pages
    public static function getRelatedDefaultSelectedIDValues()
    {
        $names = ['II'];

        return self::whereIn('name', $names)->pluck('id')->all();
    }
}
