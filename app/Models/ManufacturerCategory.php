<?php

namespace App\Models;

use App\Support\Contracts\Model\TracksUsageCount;
use App\Support\Traits\Model\PreventsDeletionIfInUse;
use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Model;

class ManufacturerCategory extends Model implements TracksUsageCount
{
    use ScopesOrderingByName;
    use PreventsDeletionIfInUse;

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
        return $this->hasMany(Manufacturer::class, 'category_id');
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
        ]);
    }

    //Implement method declared in 'TracksUsageCount' interface.
    public function getUsageCountAttribute()
    {
        return $this->manufacturers_count;
    }
}
