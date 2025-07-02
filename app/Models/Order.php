<?php

namespace App\Models;

use App\Notifications\OrderIsConfirmed;
use App\Notifications\OrderIsSentToBDM;
use App\Notifications\OrderIsSentToConfirmation;
use App\Notifications\OrderIsSentToManufacturer;
use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\CanExportRecordsAsExcel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\Commentable;
use App\Support\Traits\Model\ScopesOrderingByName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class Order extends BaseModel implements HasTitle, CanExportRecordsAsExcel
{
    /** @use HasFactory<\Database\Factories\ManufacturerFactory> */
    use HasFactory;
    use SoftDeletes;
    use Commentable;
    use ScopesOrderingByName;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    // PLPD
    const SETTINGS_PLPD_TABLE_COLUMNS_KEY = 'PLPD_orders_table_columns';
    const DEFAULT_PLPD_ORDER_BY = 'id';
    const DEFAULT_PLPD_ORDER_TYPE = 'asc';
    const DEFAULT_PLPD_PAGINATION_LIMIT = 50;

    // CMD
    const SETTINGS_CMD_TABLE_COLUMNS_KEY = 'CMD_orders_table_columns';
    const DEFAULT_CMD_ORDER_BY = 'sent_to_bdm_date';
    const DEFAULT_CMD_ORDER_TYPE = 'desc';
    const DEFAULT_CMD_PAGINATION_LIMIT = 50;

    // Statuses
    const STATUS_CREATED_NAME = 'Created';
    const STATUS_IS_SENT_TO_BDM_NAME = 'Sent to BDM';
    const STATUS_IS_SENT_TO_CONFIRMATION_NAME = 'Sent to confirmation';
    const STATUS_IS_CONFIRMED_NAME = 'Confirmed';
    const STATUS_IS_SENT_TO_MANUFACTURER_NAME = 'Sent to manufacturer';

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    protected $casts = [
        'receive_date' => 'date',
        'sent_to_bdm_date' => 'date',
        'purchase_date' => 'date',
        'sent_to_confirmation_date' => 'date',
        'confirmation_date' => 'date',
        'sent_to_manufacturer_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class)->withTrashed();;
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class)->withTrashed(); // No manual trashing/restoring
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class)->withTrashed(); // No manual trashing/restoring
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    public function getIsSentToBdmAttribute(): bool
    {
        return !is_null($this->sent_to_bdm_date);
    }

    public function getIsSentToConfirmationAttribute(): bool
    {
        return !is_null($this->sent_to_confirmation_date);
    }

    public function getIsConfirmedAttribute(): bool
    {
        return !is_null($this->confirmation_date);
    }

    public function getIsSentToManufacturerAttribute(): bool
    {
        return !is_null($this->sent_to_manufacturer_date);
    }

    public function getStatusAttribute()
    {
        if ($this->is_sent_to_manufacturer) {
            return self::STATUS_IS_SENT_TO_MANUFACTURER_NAME;
        } elseif ($this->is_confirmed) {
            return self::STATUS_IS_CONFIRMED_NAME;
        } else if ($this->is_sent_to_confirmation) {
            return self::STATUS_IS_SENT_TO_CONFIRMATION_NAME;
        } else if ($this->is_sent_to_bdm) {
            return self::STATUS_IS_SENT_TO_BDM_NAME;
        }

        return self::STATUS_CREATED_NAME;
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::updated(function ($record) {
            foreach ($record->products as $product) {
                $product->syncCurrencyWithRelatedProcess();
            }
        });

        static::deleting(function ($record) { // trashing
            foreach ($record->products as $product) {
                $product->delete();
            }

            foreach ($record->invoices as $invoice) {
                $invoice->delete();
            }
        });

        static::restored(function ($record) {
            foreach ($record->products as $product) {
                $product->restore();
            }

            foreach ($record->invoices as $invoice) {
                $invoice->restore();
            }
        });

        static::forceDeleting(function ($record) {
            foreach ($record->products as $product) {
                $product->forceDelete();
            }

            foreach ($record->invoices as $invoice) {
                $invoice->forceDelete();
            }
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
            'country',
            'currency',
            'lastComment',

            'manufacturer' => function ($manufacturersQuery) {
                $manufacturersQuery->select(
                    'manufacturers.id',
                    'manufacturers.name',
                    'bdm_user_id',
                )
                    ->with([
                        'bdm:id,name,photo',
                    ]);
            },

            'invoices' => function ($invoicesQuery) {
                $invoicesQuery->with([
                    'paymentType',
                ]);
            },
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'comments',
            'products',
            'invoices',
        ]);
    }

    public function scopeOnlySentToBdm($query)
    {
        return $query->whereNotNull('sent_to_bdm_date');
    }

    public function scopeOnlySentToManufacturer($query)
    {
        return $query->whereNotNull('sent_to_manufacturer_date');
    }

    public function scopeOnlyWithName($query)
    {
        return $query->whereNotNull('name');
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
     * Trash page is available only for 'PLPD'.
     */
    public function generateBreadcrumbs($department = null): array
    {
        // If department is declared
        if ($department) {
            $lowercasedDepartment = strtolower($department);

            $breadcrumbs = [
                ['link' => route($lowercasedDepartment . '.orders.index'), 'text' => __('Orders')],
            ];

            if ($this->trashed() && $department == 'PLPD') {
                $breadcrumbs[] = ['link' => route($lowercasedDepartment . '.orders.trash'), 'text' => __('Trash')];
            }

            $breadcrumbs[] = ['link' => route($lowercasedDepartment . '.orders.edit', $this->id), 'text' => $this->title];

            return $breadcrumbs;
        }

        // If department is null
        return [
            ['link' => null, 'text' => __('Orders')],
            ['link' => null, 'text' => $this->title],
        ];
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return $this->name ?: ('#' . $this->id);
    }

    // Implement method declared in CanExportRecordsAsExcel Interface
    public function scopeWithRelationsForExport($query)
    {
        return $query->withBasicRelations()
            ->withBasicRelationCounts()
            ->with(['comments']);
    }

    // Implement method declared in CanExportRecordsAsExcel Interface
    public function getExcelColumnValuesForExport(): array
    {
        return [
            $this->id,
        ];
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

        // Apply 'status' filter
        self::applyStatusFilter($query, $request);

        return $query;
    }

    private static function getFilterConfig(): array
    {
        return [
            'whereIn' => ['id', 'manufacturer_id', 'country_id', 'name'],
            'dateRange' => [
                'receive_date',
                'sent_to_bdm_date',
                'sent_to_manufacturer_date',
                'created_at',
                'updated_at'
            ],

            'relationEqual' => [
                [
                    'name' => 'manufacturer',
                    'attribute' => 'bdm_user_id',
                ],
            ],
        ];
    }

    public static function getFilterStatusOptions()
    {
        return [
            self::STATUS_CREATED_NAME,
            self::STATUS_IS_SENT_TO_BDM_NAME,
            self::STATUS_IS_SENT_TO_CONFIRMATION_NAME,
            self::STATUS_IS_CONFIRMED_NAME,
        ];
    }

    /**
     * Applies a status-based filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query   The query builder instance.
     * @param \Illuminate\Http\Request              $request The incoming request containing filters.
     * @return void
     */
    public static function applyStatusFilter($query, Request $request): void
    {
        $status = $request->input('status');

        if (!$status) {
            return;
        }

        $conditions = match ($status) {
            self::STATUS_CREATED_NAME => fn($q) => $q->whereNull('sent_to_bdm_date'),
            self::STATUS_IS_SENT_TO_BDM_NAME => fn($q) => $q->whereNotNull('sent_to_bdm_date')->whereNull('sent_to_confirmation_date'),
            self::STATUS_IS_SENT_TO_CONFIRMATION_NAME => fn($q) => $q->whereNotNull('sent_to_confirmation_date')->whereNull('confirmation_date'),
            self::STATUS_IS_CONFIRMED_NAME => fn($q) => $q->whereNotNull('confirmation_date'),
            default => null,
        };

        if ($conditions) {
            $conditions($query);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Adding default query params to request
    |--------------------------------------------------------------------------
    */

    public static function addDefaultPLPDQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_PLPD_ORDER_BY',
            'DEFAULT_PLPD_ORDER_TYPE',
            'DEFAULT_PLPD_PAGINATION_LIMIT',
        );
    }

    public static function addDefaultCMDQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_CMD_ORDER_BY',
            'DEFAULT_CMD_ORDER_TYPE',
            'DEFAULT_CMD_PAGINATION_LIMIT',
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create & Update
    |--------------------------------------------------------------------------
    */

    // PLPD part

    public static function createByPLPDFromRequest($request)
    {
        $record = self::create($request->only([
            'manufacturer_id',
            'country_id',
            'receive_date',
        ]));

        // HasMany relations
        $record->storeCommentFromRequest($request);

        // Store products
        $products = $request->input('products', []);

        foreach ($products as $product) {
            $record->products()->create([
                'process_id' => $product['process_id'],
                'quantity' => $product['quantity'],
            ]);
        }
    }

    public function updateByPLPDFromRequest($request)
    {
        $this->update($request->all());

        // HasMany relations
        $this->storeCommentFromRequest($request);
    }

    // CMD part

    public function updateByCMDFromRequest($request)
    {
        $this->update($request->all());

        // Update 'purchase_date'
        if (is_null($this->purchase_date)) {
            $this->update([
                'purchase_date' => now(),
            ]);
        }

        // HasMany relations
        $this->storeCommentFromRequest($request);

        // Update products
        foreach ($request->products as $id => $product) {
            $orderProduct = $this->products()->findOrFail($id);

            $orderProduct->update([
                'price' => $product['price'],
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute togglings
    |--------------------------------------------------------------------------
    */

    /**
     * PLPD logistician sends order to CMD BDM
     */
    public function toggleIsSentToBDMAttribute(Request $request)
    {
        $action = $request->input('action');

        if ($action == 'send' && !$this->is_sent_to_bdm) {
            $this->sent_to_bdm_date = now();
            $this->save();

            // Notify specific users
            $notification = new OrderIsSentToBDM($this);
            User::notifyUsersBasedOnPermission($notification, 'receive-notification-when-PLPD-order-is-sent-to-CMD-BDM');
        }
    }

    /**
     * CMD BDM sends order to PLPD Logistician for confirmation
     */
    public function toggleIsSentToConfirmationAttribute(Request $request)
    {
        $action = $request->input('action');

        if ($action == 'send' && !$this->is_sent_to_confirmation) {
            $this->sent_to_confirmation_date = now();
            $this->save();

            // Notify specific users
            $notification = new OrderIsSentToConfirmation($this);
            User::notifyUsersBasedOnPermission($notification, 'receive-notification-when-CMD-order-is-sent-for-confirmation');
        }
    }

    /**
     * PLPD Logistician confirms order, CMD continues process
     */
    public function toggleIsConfirmedAttribute(Request $request)
    {
        $action = $request->input('action');

        if ($action == 'confirm' && !$this->is_confirmed) {
            $this->confirmation_date = now();
            $this->save();

            // Notify specific users
            $notification = new OrderIsConfirmed($this);
            User::notifyUsersBasedOnPermission($notification, 'receive-notification-when-PLPD-order-is-confirmed');
        }
    }

    /**
     * CMD BDM sends order to manufacturer (not real erp process)
     *
     * Notification is send to both PLPD Logisticians and DD Designers
     */
    public function toggleIsSentToManufacturerAttribute(Request $request)
    {
        $action = $request->input('action');

        if ($action == 'send' && !$this->is_sent_to_manufacturer) {
            $this->sent_to_manufacturer_date = now();
            $this->save();

            // Notify specific users
            $notification = new OrderIsSentToManufacturer($this);
            User::notifyUsersBasedOnPermission($notification, 'receive-notification-when-CMD-order-is-sent-to-manufacturer');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public function canAttachNewInvoice()
    {
        // Order must be sent to manufacturer
        if (!$this->is_sent_to_manufacturer) {
            return false;
        }

        // Order shouldn`t have final or full invoice payment types
        if ($this->invoices->whereIn('payment_type_id', [
            InvoicePaymentType::FINAL_PAYMENT_ID,
            InvoicePaymentType::FULL_PAYMENT_ID,
        ])->count()) {
            return false;
        }

        return true;
    }

    public function shouldHaveInvoiceOfFinalPaymentType()
    {
        if ($this->invoices->count() == 0) {
            return false;
        }

        return $this->invoices->where('payment_type_id', InvoicePaymentType::PREPAYMENT_ID)->count() > 0
            && $this->invoices->where('payment_type_id', InvoicePaymentType::FINAL_PAYMENT_ID)->count() == 0;
    }

    public static function getDefaultPLPDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-PLPD-orders')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-PLPD-orders')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'BDM', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Status', 'order' => $order++, 'width' => 114, 'visible' => 1],
            ['name' => 'Receive date', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Products', 'order' => $order++, 'width' => 126, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Sent to BDM', 'order' => $order++, 'width' => 160, 'visible' => 1],

            ['name' => 'PO date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'PO №', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Currency', 'order' => $order++, 'width' => 84, 'visible' => 1],
            ['name' => 'Sent to confirmation', 'order' => $order++, 'width' => 244, 'visible' => 1],
            ['name' => 'Confirmation date', 'order' => $order++, 'width' => 172, 'visible' => 1],

            ['name' => 'Sent to manufacturer', 'order' => $order++, 'width' => 164, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }

    public static function getDefaultCMDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-CMD-orders')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-CMD-orders')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'BDM', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Status', 'order' => $order++, 'width' => 114, 'visible' => 1],
            ['name' => 'Receive date', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Products', 'order' => $order++, 'width' => 126, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Sent to BDM', 'order' => $order++, 'width' => 160, 'visible' => 1],

            ['name' => 'PO date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'PO №', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Currency', 'order' => $order++, 'width' => 84, 'visible' => 1],
            ['name' => 'Sent to confirmation', 'order' => $order++, 'width' => 244, 'visible' => 1],
            ['name' => 'Confirmation date', 'order' => $order++, 'width' => 172, 'visible' => 1],

            ['name' => 'Sent to manufacturer', 'order' => $order++, 'width' => 164, 'visible' => 1],

            ['name' => 'Expected dispatch date', 'order' => $order++, 'width' => 190, 'visible' => 1],
            ['name' => 'Invoices', 'order' => $order++, 'width' => 120, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }
}
