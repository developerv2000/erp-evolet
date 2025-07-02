<?php

namespace App\Models;

use App\Support\Contracts\Model\HasTitle;
use App\Support\Traits\Model\UploadsFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model implements HasTitle
{
    use SoftDeletes; // No manual trashing/restoring. Trashed when order parent is trashed.
    use UploadsFile;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const FILE_PATH = 'private/invoices';

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    protected $casts = [
        'receive_date' => 'date',
        'sent_for_payment_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function paymentType()
    {
        return $this->belongsTo(InvoicePaymentType::class, 'payment_type_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    public function getPdfAssetUrlAttribute(): string
    {
        return asset(self::FILE_PATH . '/' . $this->filename);
    }

    public function getPdfPathAttribute()
    {
        return public_path(self::FILE_PATH . '/' . $this->filename);
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithBasicRelations($query)
    {
        return $query->with([
            'order',
            'paymentType',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    /**
     * Implement method defined in BaseModel abstract class.
     *
     * Used by 'PLPD' and 'CDM' departments.
     *
     */
    public function generateBreadcrumbs($department = null): array
    {
        // If department is declared
        if ($department) {
            $lowercasedDepartment = strtolower($department);

            return [
                ['link' => route($lowercasedDepartment . '.orders.index'), 'text' => __('Orders')],
                ['link' => route($lowercasedDepartment . '.orders.edit', $this->order_id), 'text' => $this->order->title],
                ['link' => route($lowercasedDepartment . '.order-products.index'), 'text' => __('Invoices')],
                ['link' => route($lowercasedDepartment . '.order-products.edit', $this->id), 'text' => $this->title],
            ];
        }

        // If department is null
        return [
            ['link' => null, 'text' => __('Orders')],
            ['link' => null, 'text' => __('Invoices')],
            ['link' => null, 'text' => $this->title],
        ];
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return $this->paymentType->name . ' #' . $this->id;
    }

    /*
    |--------------------------------------------------------------------------
    | Create & Update
    |--------------------------------------------------------------------------
    */

    // CMD part
    public static function createByCMDFromRequest($request)
    {
        // Merge 'payment_type_id', if order should have invoice of final payment type
        $order = Order::findorfail($request->input('order_id'));

        if ($order->shouldHaveInvoiceOfFinalPaymentType()) {
            $request->merge([
                'payment_type_id' => InvoicePaymentType::FINAL_PAYMENT_ID,
            ]);
        }

        $record = self::create($request->all());

        $record->uploadFile('filename', public_path(self::FILE_PATH), uniqid());
    }

    public function updateByCMDFromRequest($request)
    {
        $this->update($request->all());
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */
}
