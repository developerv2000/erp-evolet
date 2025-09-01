<?php

namespace App\Models;

use App\Support\Traits\Model\FindsRecordByName;
use Illuminate\Database\Eloquent\Model;

class ShipmentDestination extends Model
{
    use FindsRecordByName;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const RIGA_NAME = 'Riga';
    const DESTINATION_COUNTRY_NAME = 'Destination country';

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
