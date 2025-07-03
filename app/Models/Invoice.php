<?php

namespace App\Models;

use App\Notifications\InvoiceIsSentForPayment;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Traits\Model\FormatsAttributeForDateTimeInput;
use App\Support\Traits\Model\UploadsFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model implements HasTitle
{
    use SoftDeletes; // No manual trashing/restoring. Trashed when order parent is trashed.
    use UploadsFile;
    use FormatsAttributeForDateTimeInput;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const PDF_PATH = 'private/invoices';

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    protected $casts = [
        'receive_date' => 'datetime',
        'sent_for_payment_date' => 'datetime',
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
        return asset(self::PDF_PATH . '/' . $this->pdf);
    }

    public function getPdfPathAttribute()
    {
        return public_path(self::PDF_PATH . '/' . $this->pdf);
    }

    public function getIsSentForPaymentAttribute(): bool
    {
        return !is_null($this->sent_for_payment_date);
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
                ...$this->order->generateBreadcrumbs($lowercasedDepartment),
                ['link' => route($lowercasedDepartment . '.invoices.index', $this->order_id), 'text' => __('Invoices')],
                ['link' => route($lowercasedDepartment . '.invoices.edit', $this->id), 'text' => $this->title],
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

        $record->uploadFile('pdf', public_path(self::PDF_PATH), uniqid());
    }

    public function updateByCMDFromRequest($request)
    {
        $this->update($request->all());

        $this->uploadFile('pdf', public_path(self::PDF_PATH), uniqid());
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * CMD BDM sends invoice to PRD Financier for payment
     */
    public function toggleIsSentForPaymentAttribute(Request $request)
    {
        $action = $request->input('action');

        if ($action == 'send' && !$this->is_sent_for_payment) {
            $this->sent_for_payment_date = now();
            $this->save();

            // Notify specific users
            $notification = new InvoiceIsSentForPayment($this);
            User::notifyUsersBasedOnPermission($notification, 'receive-notification-when-CMD-invoice-is-sent-for-payment');
        }
    }
}
