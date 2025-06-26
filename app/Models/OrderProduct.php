<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\CanExportRecordsAsExcel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\Commentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use function PHPUnit\Framework\isNull;

class OrderProduct extends BaseModel implements HasTitle, CanExportRecordsAsExcel
{
    /** @use HasFactory<\Database\Factories\OrderProductFactory> */
    use HasFactory;
    use SoftDeletes; // Trashed when order parent is trashed
    use Commentable;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    // PLPD
    const SETTINGS_PLPD_TABLE_COLUMNS_KEY = 'PLPD_order_products_table_columns';
    const DEFAULT_PLPD_ORDER_BY = 'order_id';
    const DEFAULT_PLPD_ORDER_TYPE = 'asc';
    const DEFAULT_PLPD_PAGINATION_LIMIT = 50;

    // CMD
    const SETTINGS_CMD_TABLE_COLUMNS_KEY = 'CMD_order_products_table_columns';
    const DEFAULT_CMD_ORDER_BY = 'order_sent_to_bdm_date';
    const DEFAULT_CMD_ORDER_TYPE = 'desc';
    const DEFAULT_CMD_PAGINATION_LIMIT = 50;

    // DD
    const SETTINGS_DD_TABLE_COLUMNS_KEY = 'DD_order_products_table_columns';
    const DEFAULT_DD_ORDER_BY = 'order_sent_to_manufacturer_date';
    const DEFAULT_DD_ORDER_TYPE = 'desc';
    const DEFAULT_DD_PAGINATION_LIMIT = 50;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    protected $casts = [
        'date_of_sending_new_layout_to_manufacturer' => 'date',
        'date_of_receiving_print_proof_from_manufacturer' => 'date',
        'layout_approved_date' => 'date',
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

    public function process()
    {
        return $this->belongsTo(Process::class)->withTrashed();
    }

    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            Process::class,
            'id', // Foreign key on the Processes table
            'id', // Foreign key on the Products table
            'process_id', // Local key on the Orders table
            'product_id' // Local key on the Processes table
        )->withTrashedParents()->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    public function getLayoutApprovedAttribute(): bool
    {
        return !is_null($this->layout_approved_date);
    }

    public function getTotalPriceAttribute()
    {
        $total = $this->quantity * $this->price;

        return floor($total * 100) / 100;
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::created(function ($record) {
            $record->syncPriceWithRelatedProcess();
        });

        static::updated(function ($record) {
            $record->syncPriceWithRelatedProcess();
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
            'lastComment',

            'order' => function ($orderQuery) {
                $orderQuery->with([ // ->withBasicRelations not used because of redundant 'lastComment'
                    'country',
                    'currency',

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
                ]);
            },

            'process' => function ($processQuery) {
                $processQuery->withRelationsForOrderProduct()
                    ->withOnlyRequiredSelectsForOrderProduct();
            },
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'comments',
        ]);
    }

    public function scopeOnlySentToBdm($query)
    {
        return $query->whereHas('order', function ($orderQuery) {
            $orderQuery->onlySentToBdm();
        });
    }

    public function scopeOnlySentToManufacturer($query)
    {
        return $query->whereHas('order', function ($orderQuery) {
            $orderQuery->onlySentToManufacturer();
        });
    }

    /**
     * Add 'order_sent_to_bdm_date' attribute.
     *
     * Used while ordering products by 'order_sent_to_bdm_date'
     */
    public function scopeWithOrderSentToBdmDateAttribute($query)
    {
        return $query
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->selectRaw('orders.sent_to_bdm_date as order_sent_to_bdm_date');
    }

    /**
     * Add 'order_sent_to_manufacturer_date' attribute.
     *
     * Used while ordering products by 'order_sent_to_manufacturer_date'
     */
    public function scopeWithOrderSentToManufacturerDateAttribute($query)
    {
        return $query
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->selectRaw('orders.sent_to_manufacturer_date as order_sent_to_manufacturer_date');
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    /**
     * Implement method defined in BaseModel abstract class.
     *
     * Used by 'PLPD', 'CDM', 'DD' departments.
     *
     * No orders page for 'DD'.
     */
    public function generateBreadcrumbs($department = null): array
    {
        // If department is declared
        if ($department) {
            $lowercasedDepartment = strtolower($department);
            $breadcrumbs = [];

            // If departments has 'orders' page
            if ($department != 'DD') {
                $breadcrumbs[] = ['link' => route($lowercasedDepartment . '.orders.index'), 'text' => __('Orders')];
                $breadcrumbs[] = ['link' => route($lowercasedDepartment . '.orders.edit', $this->order_id), 'text' => $this->order->title];
            }

            $breadcrumbs[] = ['link' => route($lowercasedDepartment . '.order-products.index'), 'text' => __('Products')];
            $breadcrumbs[] = ['link' => route($lowercasedDepartment . '.order-products.edit', $this->id), 'text' => $this->title];

            return $breadcrumbs;
        }

        // If department is null
        return [
            ['link' => null, 'text' => __('Products')],
            ['link' => null, 'text' => $this->title],
        ];
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return ($this->order->name ? $this->order->name . ' ' : '') . '#' . $this->id;
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
            'whereEqual' => ['process_id', 'new_layout', 'order_id'],
            'whereIn' => ['id'],
            'dateRange' => [
                'date_of_sending_new_layout_to_manufacturer',
                'date_of_receiving_print_proof_from_manufacturer',
                'layout_approved_date',
                'created_at',
                'updated_at'
            ],

            'relationEqual' => [
                [
                    'name' => 'order.manufacturer',
                    'attribute' => 'bdm_user_id',
                ],
            ],

            'relationIn' => [
                [
                    'name' => 'process.product',
                    'attribute' => 'manufacturer_id',
                ],

                [
                    'name' => 'process',
                    'attribute' => 'country_id',
                ],

                [
                    'name' => 'process',
                    'attribute' => 'marketing_authorization_holder_id',
                ],

                [
                    'name' => 'process',
                    'attribute' => 'trademark_en',
                ],

                [
                    'name' => 'process',
                    'attribute' => 'trademark_ru',
                ],
            ],
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
        $query->whereHas('order', function ($orderQuery) use ($request) {
            Order::applyStatusFilter($orderQuery, $request);
        });
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

    public static function addDefaultDDQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_DD_ORDER_BY',
            'DEFAULT_DD_ORDER_TYPE',
            'DEFAULT_DD_PAGINATION_LIMIT',
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
        $record = self::create($request->all());

        // HasMany relations
        $record->storeCommentFromRequest($request);
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

        // HasMany relations
        $this->storeCommentFromRequest($request);
    }

    // DD part

    function updateByDDFromRequest($request)
    {
        $this->fill($request->safe()->all());

        // Validate 'date_of_receiving_print_proof_from_manufacturer' attribute
        if (!$this->new_layout) {
            $this->date_of_receiving_print_proof_from_manufacturer = null;
        }

        $this->save();

        // HasMany relations
        $this->storeCommentFromRequest($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Used on order-products.index pages for ordering.
     */
    public static function addJoinsForOrdering($query, $request)
    {
        if ($request->input('order_by') == 'order_sent_to_bdm_date') {
            $query->withOrderSentToBdmDateAttribute();
        }

        if ($request->input('order_by') == 'order_sent_to_manufacturer_date') {
            $query->withOrderSentToManufacturerDateAttribute();
        }

        return $query;
    }

    /**
     * Sync the price of the related Process with this model.
     * If the price differs, it updates the 'increased_price' field.
     *
     * Used in models created/updated events.
     */
    public function syncPriceWithRelatedProcess(): void
    {
        if ($this->price && ($this->process->agreed_price != $this->price)) {
            $this->process->update([
                'increased_price' => $this->price
            ]);
        }
    }

    /**
     * Sync the currency of the related Process with this model.
     *
     * Used in Order models updated event!
     */
    public function syncCurrencyWithRelatedProcess(): void
    {
        $this->refresh();

        if ($this->order->currency_id && ($this->process->currency_id != $this->order->currency_id)) {
            $this->process->update([
                'currency_id' => $this->order->currency_id,
            ]);
        }
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
            ['name' => 'Order', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Receive date', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 112, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Sent to BDM', 'order' => $order++, 'width' => 160, 'visible' => 1],

            ['name' => 'PO date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'PO №', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'TM Eng', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'TM Rus', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Generic', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Form', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Dosage', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Pack', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Price', 'order' => $order++, 'width' => 70, 'visible' => 1],
            ['name' => 'Total price', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Currency', 'order' => $order++, 'width' => 84, 'visible' => 1],
            ['name' => 'Sent to confirmation', 'order' => $order++, 'width' => 244, 'visible' => 1],
            ['name' => 'Confirmation date', 'order' => $order++, 'width' => 172, 'visible' => 1],

            ['name' => 'Sent to manufacturer', 'order' => $order++, 'width' => 164, 'visible' => 1],
            ['name' => 'Layout status', 'order' => $order++, 'width' => 126, 'visible' => 1],
            ['name' => 'Layout sent date', 'order' => $order++, 'width' => 178, 'visible' => 1],
            ['name' => 'Print proof receive date', 'order' => $order++, 'width' => 228, 'visible' => 1],
            ['name' => 'Box article', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Layout approved date', 'order' => $order++, 'width' => 188, 'visible' => 1],

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
            ['name' => 'Order', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Receive date', 'order' => $order++, 'width' => 138, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 112, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Sent to BDM', 'order' => $order++, 'width' => 160, 'visible' => 1],

            ['name' => 'PO date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'PO №', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'TM Eng', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'TM Rus', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Generic', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Form', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Dosage', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Pack', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Price', 'order' => $order++, 'width' => 70, 'visible' => 1],
            ['name' => 'Total price', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Currency', 'order' => $order++, 'width' => 84, 'visible' => 1],
            ['name' => 'Sent to confirmation', 'order' => $order++, 'width' => 244, 'visible' => 1],
            ['name' => 'Confirmation date', 'order' => $order++, 'width' => 172, 'visible' => 1],

            ['name' => 'Sent to manufacturer', 'order' => $order++, 'width' => 164, 'visible' => 1],
            ['name' => 'Layout status', 'order' => $order++, 'width' => 126, 'visible' => 1],
            ['name' => 'Layout sent date', 'order' => $order++, 'width' => 178, 'visible' => 1],
            ['name' => 'Print proof receive date', 'order' => $order++, 'width' => 228, 'visible' => 1],
            ['name' => 'Box article', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Layout approved date', 'order' => $order++, 'width' => 188, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }

    public static function getDefaultDDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-DD-orders')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-DD-orders')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'Layout approved', 'order' => $order++, 'width' => 134, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 112, 'visible' => 1],
            ['name' => 'Sent to BDM', 'order' => $order++, 'width' => 160, 'visible' => 1],

            ['name' => 'PO №', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'TM Eng', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'TM Rus', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Generic', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Form', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Dosage', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Pack', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Sent to manufacturer', 'order' => $order++, 'width' => 164, 'visible' => 1],

            ['name' => 'Layout status', 'order' => $order++, 'width' => 126, 'visible' => 1],
            ['name' => 'Layout sent date', 'order' => $order++, 'width' => 178, 'visible' => 1],
            ['name' => 'Print proof receive date', 'order' => $order++, 'width' => 228, 'visible' => 1],
            ['name' => 'Box article', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Layout approved date', 'order' => $order++, 'width' => 188, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }
}
