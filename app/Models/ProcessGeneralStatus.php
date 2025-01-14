<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessGeneralStatus extends Model
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

    public function childs()
    {
        return $this->hasMany(ProcessStatus::class, 'general_status_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Pluck all unique name_for_analysts
     *
     * Used on process filtering
     */
    public static function getUniqueNamesForAnalysts()
    {
        return self::orderBy('id', 'asc')
            ->pluck('name_for_analysts')
            ->unique();
    }
}
