<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class ProcessStatus extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function generalStatus()
    {
        return $this->belongsTo(ProcessGeneralStatus::class, 'general_status_id');
    }

    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Get all records restricted by permissions
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getAllRestrictedByPermissions()
    {
        $records = self::query();

        // Query records, applying additional filters if the user has not specific permissions
        if (Gate::denies('upgrade-MAD-VPS-status-after-contract-stage')) {
            $records = $records->whereHas('generalStatus', function ($generalStatusesQuery) {
                $generalStatusesQuery->where('requires_permission', false);
            });
        }

        $records = $records->orderBy('id', 'asc')->get();

        return $records;
    }
}
