<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayerCompany extends Model
{
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
