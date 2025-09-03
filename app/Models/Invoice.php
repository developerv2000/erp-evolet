<?php

namespace App\Models;

use App\Notifications\InvoiceIsSentForPayment;
use App\Notifications\InvoicePaymentIsCompleted;
use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\FormatsAttributeForDateTimeInput;
use App\Support\Traits\Model\UploadsFile;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class Invoice extends BaseModel implements HasTitle
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
    const PAYMENT_CONFIRMATION_DOCUMENT_PATH = 'private/payment-confirmation-documents';

    // CMD
    const SETTINGS_CMD_TABLE_COLUMNS_KEY = 'CMD_invoices_table_columns';
    const DEFAULT_CMD_ORDER_BY = 'updated_at';
    const DEFAULT_CMD_ORDER_TYPE = 'desc';
    const DEFAULT_CMD_PAGINATION_LIMIT = 50;

    // PRD
    const SETTINGS_PRD_TABLE_COLUMNS_KEY = 'PRD_invoices_table_columns';
    const DEFAULT_PRD_ORDER_BY = 'updated_at';
    const DEFAULT_PRD_ORDER_TYPE = 'desc';
    const DEFAULT_PRD_PAGINATION_LIMIT = 50;

    // PLPD
    const SETTINGS_PLPD_TABLE_COLUMNS_KEY = 'PLPD_invoices_table_columns';
    const DEFAULT_PLPD_ORDER_BY = 'updated_at';
    const DEFAULT_PLPD_ORDER_TYPE = 'desc';
    const DEFAULT_PLPD_PAGINATION_LIMIT = 50;

    // ELD
    const SETTINGS_ELD_TABLE_COLUMNS_KEY = 'ELD_invoices_table_columns';
    const DEFAULT_ELD_ORDER_BY = 'updated_at';
    const DEFAULT_ELD_ORDER_TYPE = 'desc';
    const DEFAULT_ELD_PAGINATION_LIMIT = 50;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    protected $casts = [
        'receive_date' => 'datetime',
        'sent_for_payment_date' => 'datetime',
        'accepted_by_financier_date' => 'datetime',
        'payment_request_date_by_financier' => 'datetime',
        'payment_date' => 'datetime',
        'payment_completed_date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function type()
    {
        return $this->belongsTo(InvoiceType::class, 'type_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(InvoicePaymentType::class, 'payment_type_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function orderProducts()
    {
        return $this->belongsToMany(OrderProduct::class);
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

    public function getPdfFilePathAttribute()
    {
        return public_path(self::PDF_PATH . '/' . $this->pdf);
    }

    public function getPaymentConfirmationDocumentAssetUrlAttribute(): string
    {
        return asset(self::PAYMENT_CONFIRMATION_DOCUMENT_PATH . '/' . $this->payment_confirmation_document);
    }

    public function getIsSentForPaymentAttribute(): bool
    {
        return !is_null($this->sent_for_payment_date);
    }

    public function getIsAcceptedByFinancierAttribute(): bool
    {
        return !is_null($this->accepted_by_financier_date);
    }

    public function getPaymentIsCompletedAttribute(): bool
    {
        return !is_null($this->payment_completed_date);
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::forceDeleting(function ($record) {
            $record->orderProducts()->detach();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeWithBasicRelations($query)
    {
        return $query->with([
            'paymentType',

            'order' => function ($orderQuery) {
                $orderQuery->with([ // ->withBasicRelations not used because of redundant relations
                    'country',

                    'manufacturer' => function ($manufacturersQuery) {
                        $manufacturersQuery->select(
                            'manufacturers.id',
                            'manufacturers.name',
                        );
                    },
                ]);
            },
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'orderProducts',
        ]);
    }

    public function scopeOnlySentForPayment($query)
    {
        return $query->whereNotNull('sent_for_payment_date');
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    /**
     * Implement method defined in BaseModel abstract class.
     *
     * Used by 'PLPD', 'CMD' and 'PRD' departments.
     */
    public function generateBreadcrumbs($department = null): array
    {
        // If department is declared
        if ($department) {
            $lowercasedDepartment = strtolower($department);

            return [
                ...$this->order->generateBreadcrumbs($department),
                ['link' => route($lowercasedDepartment . '.invoices.index', ['order_id[]' => $this->order_id]), 'text' => __('Invoices')],
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
        return $this->paymentType->name . ' ' . ($this->number ?: ('#' . $this->id));
    }

    /*
    |--------------------------------------------------------------------------
    | Filtering
    |--------------------------------------------------------------------------
    */

    public static function filterQueryForRequest($query, $request)
    {
        // Apply base filters using helper
        $query = QueryFilterHelper::applyFilters($query, $request, self::getFilterConfig());

        return $query;
    }

    private static function getFilterConfig(): array
    {
        return [
            'whereEqual' => ['payment_type_id', 'number'],
            'whereIn' => ['id', 'order_id'],
            'dateRange' => [
                'receive_date',
                'sent_for_payment_date',
                'created_at',
                'updated_at'
            ],

            'relationEqual' => [
                [
                    'name' => 'order',
                    'attribute' => 'manufacturer_id',
                ],

                [
                    'name' => 'order',
                    'attribute' => 'country_id',
                ],
            ],

            'relationEqualAmbiguous' => [
                [
                    'name' => 'orderProducts',
                    'attribute' => 'order_product_id',
                    'ambiguousAttribute' => 'order_products.id',
                ],
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Adding default query params to request
    |--------------------------------------------------------------------------
    */

    public static function addDefaultCMDQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_CMD_ORDER_BY',
            'DEFAULT_CMD_ORDER_TYPE',
            'DEFAULT_CMD_PAGINATION_LIMIT',
        );
    }

    public static function addDefaultPRDQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_PRD_ORDER_BY',
            'DEFAULT_PRD_ORDER_TYPE',
            'DEFAULT_PRD_PAGINATION_LIMIT',
        );
    }

    public static function addDefaultPLPDQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_PLPD_ORDER_BY',
            'DEFAULT_PLPD_ORDER_TYPE',
            'DEFAULT_PLPD_PAGINATION_LIMIT',
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create & Update
    |--------------------------------------------------------------------------
    */

    // CMD part
    public static function createByCMDFromRequest($request)
    {
        $order = Order::findorfail($request->input('order_id'));

        // Create invoice
        $record = self::create($request->all());

        // Attach all products of order, for invoice of PREPAYMENT type
        if ($record->payment_type_id == InvoicePaymentType::PREPAYMENT_ID) {
            $productIDs = $order->products()->pluck('id')->toArray();
            $record->orderProducts()->attach($productIDs);
        } else {
            // Attach only selected products for invoices of
            // FULL_PAYMENT and FINAL_PAYMENT types
            $selectedOrderProducts = $request->input('order_products', []);
            $record->orderProducts()->attach($selectedOrderProducts);
        }

        // Upload PDF file
        $record->uploadFile('pdf', public_path(self::PDF_PATH));
    }

    public function updateByCMDFromRequest($request)
    {
        $this->update($request->all());

        // Sycn orderProducts
        $selectedOrderProducts = $request->input('order_products', []);
        $this->orderProducts()->sync($selectedOrderProducts);

        // Upload PDF file
        $this->uploadFile('pdf', public_path(self::PDF_PATH));
    }

    public function updateByPRDFromRequest($request)
    {
        $this->update($request->all());

        // Validate 'accepted_by_financier_date' attribute
        if (!$this->is_accepted_by_financier) {
            $this->accepted_by_financier_date = now();
            $this->save();
        }

        // Upload SWIFT file
        $this->uploadFile('payment_confirmation_document', public_path(self::PAYMENT_CONFIRMATION_DOCUMENT_PATH));
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute togglings
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

    /**
     * PRD Financier accepts invoice from CMD BDM
     */
    public function toggleIsAcceptedByFinancierAttribute(Request $request)
    {
        $action = $request->input('action');

        if ($action == 'accept' && !$this->is_accepted_by_financier) {
            $this->accepted_by_financier_date = now();
            $this->save();
        }
    }

    /**
     * PRD Financier completes payment
     */
    public function togglePaymentIsCompletedAttribute(Request $request)
    {
        $action = $request->input('action');

        if ($action == 'complete' && !$this->payment_is_completed) {
            $this->payment_completed_date = now();
            $this->save();

            // Notify specific users
            $notification = new InvoicePaymentIsCompleted($this);
            User::notifyUsersBasedOnPermission($notification, 'receive-notification-when-PRD-invoice-payment-is-completed');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Sync orderProducts between invoices based on payment type.
     *
     * This method syncs FINAL_PAYMENT invoice's orderProducts from a PREPAYMENT invoice.
     * Used by `createByCMDFromRequest`.
     */
    public function syncOrderProductsFromRelatedPrepaymentInvoice(): void
    {
        $this->refresh();

        if ($this->payment_type_id !== InvoicePaymentType::FINAL_PAYMENT_ID) {
            return;
        }

        $prepaymentInvoice = $this->order->invoices()
            ->where('payment_type_id', InvoicePaymentType::PREPAYMENT_ID)
            ->first();

        if ($prepaymentInvoice) {
            $this->orderProducts()->sync($prepaymentInvoice->orderProducts->pluck('id')->all());
        }
    }

    /**
     * Sync orderProducts between invoices based on payment type.
     *
     * This method syncs a PREPAYMENT invoice's orderProducts to a FINAL_PAYMENT invoice.
     * Used by `updateByPRDFromRequest`.
     */
    public function syncOrderProductsWithRelatedFinalPaymentInvoice(): void
    {
        $this->refresh();

        if ($this->payment_type_id !== InvoicePaymentType::PREPAYMENT_ID) {
            return;
        }

        $finalPaymentInvoice = $this->order->invoices()
            ->where('payment_type_id', InvoicePaymentType::FINAL_PAYMENT_ID)
            ->first();

        if ($finalPaymentInvoice) {
            $finalPaymentInvoice->orderProducts()->sync($this->orderProducts->pluck('id')->all());
        }
    }

    public static function getDefaultCMDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-CMD-invoices')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-CMD-invoices')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'Receive date', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Payment type', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Products', 'order' => $order++, 'width' => 82, 'visible' => 1],
            ['name' => 'Sent for payment date', 'order' => $order++, 'width' => 198, 'visible' => 1],
            ['name' => 'Payment completed', 'order' => $order++, 'width' => 158, 'visible' => 1],
            ['name' => 'PDF', 'order' => $order++, 'width' => 144, 'visible' => 100],

            ['name' => 'Order', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],

            ['name' => 'Accepted date', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Payment request date', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Payment date', 'order' => $order++, 'width' => 122, 'visible' => 1],
            ['name' => 'Invoice №', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'SWIFT', 'order' => $order++, 'width' => 144, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }

    public static function getDefaultPRDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-PRD-invoices')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-PRD-invoices')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'Receive date', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Payment type', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Sent for payment date', 'order' => $order++, 'width' => 198, 'visible' => 1],
            ['name' => 'Payment completed', 'order' => $order++, 'width' => 158, 'visible' => 1],
            ['name' => 'PDF', 'order' => $order++, 'width' => 144, 'visible' => 100],

            ['name' => 'Order', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],

            ['name' => 'Accepted date', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Payment request date', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Payment date', 'order' => $order++, 'width' => 122, 'visible' => 1],
            ['name' => 'Invoice №', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'SWIFT', 'order' => $order++, 'width' => 144, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }

    public static function getDefaultPLPDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-PLPD-invoices')) {
            return null;
        }

        $order = 1;
        $columns = array();

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'Receive date', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Payment type', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Sent for payment date', 'order' => $order++, 'width' => 198, 'visible' => 1],
            ['name' => 'Payment completed', 'order' => $order++, 'width' => 158, 'visible' => 1],
            ['name' => 'PDF', 'order' => $order++, 'width' => 144, 'visible' => 100],

            ['name' => 'Order', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],

            ['name' => 'Accepted date', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Payment request date', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Payment date', 'order' => $order++, 'width' => 122, 'visible' => 1],
            ['name' => 'Invoice №', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'SWIFT', 'order' => $order++, 'width' => 144, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }

    public static function getDefaultELDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-ELD-invoices')) {
            return null;
        }

        $order = 1;
        $columns = array();

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'Receive date', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Payment type', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Sent for payment date', 'order' => $order++, 'width' => 198, 'visible' => 1],
            ['name' => 'Payment completed', 'order' => $order++, 'width' => 158, 'visible' => 1],
            ['name' => 'PDF', 'order' => $order++, 'width' => 144, 'visible' => 100],

            ['name' => 'Order', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],

            ['name' => 'Accepted date', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Payment request date', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Payment date', 'order' => $order++, 'width' => 122, 'visible' => 1],
            ['name' => 'Invoice №', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'SWIFT', 'order' => $order++, 'width' => 144, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }
}
