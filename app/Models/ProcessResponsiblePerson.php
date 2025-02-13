<?php

namespace App\Models;

use App\Support\Contracts\Model\TracksUsageCount;
use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Model;

class ProcessResponsiblePerson extends Model implements TracksUsageCount
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

    public function processes()
    {
        return $this->hasMany(Process::class, 'responsible_person_id');
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
        ]);
    }

    //Implement method declared in 'TracksUsageCount' interface.
    public function getUsageCountAttribute()
    {
        return $this->processes_count;
    }
}
