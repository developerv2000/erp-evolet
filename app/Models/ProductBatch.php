<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class)->withTrashed();
    }
}
