<?php

namespace App\Models;

use App\Support\Traits\Model\FindsRecordByName;
use Illuminate\Database\Eloquent\Model;

class ShipmentType extends Model
{
    use FindsRecordByName;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const AUTO_TYPE_NAME = 'Auto';
    const AIR_TYPE_NAME = 'Air';
    const SEA_TYPE_NAME = 'Sea';

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

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
