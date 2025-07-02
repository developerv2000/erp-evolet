<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePaymentType extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const PREPAYMENT_NAME = 'Prepayment';
    const FINAL_PAYMENT_NAME = 'Final payment';
    const FULL_PAYMENT_NAME = 'Full payment';

    const PREPAYMENT_ID = 1;
    const FINAL_PAYMENT_ID = 2;
    const FULL_PAYMENT_ID = 3;

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

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'payment_type_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scope
    |--------------------------------------------------------------------------
    */

    public function scopeWithoutFinalPayment($query)
    {
        return $query->where('id', '!=', self::FINAL_PAYMENT_ID);
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public function isPrepayment()
    {
        return $this->name == self::PREPAYMENT_NAME;
    }

    public function isFinalPayment()
    {
        return $this->name == self::FINAL_PAYMENT_NAME;
    }

    public function isFullPayment()
    {
        return $this->name == self::FULL_PAYMENT_NAME;
    }
}
