<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\CanExportRecordsAsExcel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\Commentable;
use App\Support\Traits\Model\FormatsAttributeForDateTimeInput;
use App\Support\Traits\Model\UploadsFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderProduct extends BaseModel implements HasTitle, CanExportRecordsAsExcel
{
    /** @use HasFactory<\Database\Factories\OrderProductFactory> */
    use HasFactory;
    use SoftDeletes; // No manual trashing/restoring. Trashed when order parent is trashed.
    use Commentable;
    use FormatsAttributeForDateTimeInput;
    use UploadsFile;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    // Files
    const PACKING_LIST_FILE_PATH = 'private/packing-lists';
    const COA_FILE_PATH = 'private/COAs';
    const COO_FILE_PATH = 'private/COOs';
    const DECLARATION_FOR_EUROPE_FILE_PATH = 'private/declarations-for-europe';

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

    // PRD
    const SETTINGS_PRD_TABLE_COLUMNS_KEY = 'PRD_order_products_table_columns';
    const DEFAULT_PRD_ORDER_BY = 'order_sent_to_manufacturer_date';
    const DEFAULT_PRD_ORDER_TYPE = 'desc';
    const DEFAULT_PRD_PAGINATION_LIMIT = 50;

    // MSD
    const SETTINGS_MSD_SERIALIZED_BY_MANUFACTURER_TABLE_COLUMNS_KEY = 'MSD_order_products_serialized_by_manufacturer_table_columns';
    const DEFAULT_MSD_SERIALIZED_BY_MANUFACTURER_ORDER_BY = 'order_production_start_date';
    const DEFAULT_MSD_SERIALIZED_BY_MANUFACTURER_ORDER_TYPE = 'desc';
    const DEFAULT_MSD_SERIALIZED_BY_MANUFACTURER_PAGINATION_LIMIT = 50;

    const SETTINGS_MSD_SERIALIZED_BY_US_TABLE_COLUMNS_KEY = 'MSD_order_products_serialized_by_us_table_columns';
    const DEFAULT_MSD_SERIALIZED_BY_US_ORDER_BY = 'order_production_start_date';
    const DEFAULT_MSD_SERIALIZED_BY_US_ORDER_TYPE = 'desc';
    const DEFAULT_MSD_SERIALIZED_BY_US_PAGINATION_LIMIT = 50;

    // ELD
    const SETTINGS_ELD_TABLE_COLUMNS_KEY = 'ELD_order_products_table_columns';
    const DEFAULT_ELD_ORDER_BY = 'readiness_for_shipment_from_manufacturer_date';
    const DEFAULT_ELD_ORDER_TYPE = 'desc';
    const DEFAULT_ELD_PAGINATION_LIMIT = 50;

    // Warehouse
    const SETTINGS_WAREHOUSE_TABLE_COLUMNS_KEY = 'warehouse_products_table_columns';
    const DEFAULT_WAREHOUSE_ORDER_BY = 'warehouse_arrival_date';
    const DEFAULT_WAREHOUSE_ORDER_TYPE = 'desc';
    const DEFAULT_WAREHOUSE_PAGINATION_LIMIT = 50;

    // Statuses
    const STATUS_PRODUCTION_IS_FINISHED_NAME = 'Production is finished';
    const STATUS_IS_READY_FOR_SHIPMENT_FROM_MANUFACTURER_NAME = 'Ready for shipment from manufacturer';
    const STATUS_SHIPMENT_FROM_MANUFACTURER_STARTED_NAME = 'Shipment from manufacturer started';
    const STATUS_SHIPMENT_FROM_MANUFACTURER_FINISHED_NAME = 'Shipment from manufacturer finished';
    const STATUS_ARRIVED_AT_WAREHOUSE_NAME = 'Arrived at warehouse';

    // Serialization statuses
    const STATUS_REPORT_SENT_TO_HUB_NAME = 'Report sent to hub';
    const STATUS_SERIALIZATION_CODES_REQUESTED_NAME = 'Serialization codes requested';
    const STATUS_SERIALIZATION_CODES_SENT_NAME = 'Serialization codes sent';
    const STATUS_SERIALIZATION_REPORT_RECEIVED_NAME = 'Serialization report received';

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
        'serialization_codes_request_date' => 'datetime',
        'serialization_codes_sent_date' => 'datetime',
        'serialization_report_recieved_date' => 'datetime',
        'report_sent_to_hub_date' => 'datetime',
        'production_end_date' => 'datetime',
        'readiness_for_shipment_from_manufacturer_date' => 'datetime',
        'shipment_from_manufacturer_start_date' => 'datetime',
        'delivery_to_warehouse_request_date' => 'datetime',
        'delivery_to_warehouse_rate_approved_date' => 'date',
        'delivery_to_warehouse_loading_confirmed_date' => 'date',
        'shipment_from_manufacturer_end_date' => 'datetime',
        'warehouse_arrival_date' => 'datetime',
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

    public function productionInvoices()
    {
        return $this->belongsToMany(Invoice::class)
            ->onlyProductionType();
    }

    public function deliveryToWarehouseInvoice()
    {
        return $this->hasOne(Invoice::class)
            ->onlyDeliveryToWarehouseType()
            ->where('order_id', $this->order_id);
    }

    public function exportInvoice()
    {
        return $this->hasOne(Invoice::class)
            ->onlyExportType()
            ->where('order_id', $this->order_id);
    }

    public function serializationType()
    {
        return $this->belongsTo(SerializationType::class);
    }

    public function shipmentFromManufacturerType()
    {
        return $this->belongsTo(ShipmentType::class, 'shipment_from_manufacturer_type_id');
    }

    public function shipmentFromManufacturerDestination()
    {
        return $this->belongsTo(ShipmentDestination::class, 'shipment_from_manufacturer_destination_id');
    }

    public function deliveryToWarehouseCurrency()
    {
        return $this->belongsTo(Currency::class, 'delivery_to_warehouse_currency_id');
    }

    public function payerCompany()
    {
        return $this->belongsTo(PayerCompany::class);
    }

    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    public function getProductionPrepaymentInvoiceAttribute()
    {
        return $this->productionInvoices
            ->where('payment_type_id', InvoicePaymentType::PREPAYMENT_ID)
            ->first();
    }

    public function getProductionFinalOrFullPaymentInvoiceAttribute()
    {
        return $this->productionInvoices
            ->whereIn('payment_type_id', [
                InvoicePaymentType::FINAL_PAYMENT_ID,
                InvoicePaymentType::FULL_PAYMENT_ID,
            ])
            ->first();
    }

    public function getLayoutApprovedAttribute(): bool
    {
        return !is_null($this->layout_approved_date);
    }

    public function getTotalPriceAttribute()
    {
        $total = $this->quantity * $this->price;

        return floor($total * 100) / 100;
    }

    public function getProductionIsFinishedAttribute(): bool
    {
        return !is_null($this->production_end_date);
    }

    public function getIsReadyForShipmentFromManufacturerAttribute(): bool
    {
        return !is_null($this->readiness_for_shipment_from_manufacturer_date);
    }

    public function getStatusAttribute()
    {
        if ($this->arrived_at_warehouse) {
            return self::STATUS_ARRIVED_AT_WAREHOUSE_NAME;
        } else if ($this->shipment_from_manufacturer_ended) {
            return self::STATUS_SHIPMENT_FROM_MANUFACTURER_FINISHED_NAME;
        } else if ($this->shipment_from_manufacturer_started) {
            return self::STATUS_SHIPMENT_FROM_MANUFACTURER_STARTED_NAME;
        } else if ($this->is_ready_for_shipment_from_manufacturer) {
            return self::STATUS_IS_READY_FOR_SHIPMENT_FROM_MANUFACTURER_NAME;
        } else if ($this->production_is_finished) {
            return self::STATUS_PRODUCTION_IS_FINISHED_NAME;
        }

        return $this->order->status;
    }

    public function getSerializationStatusAttribute()
    {
        if ($this->report_sent_to_hub_date) {
            return self::STATUS_REPORT_SENT_TO_HUB_NAME;
        } else if ($this->serialization_report_recieved_date) {
            return self::STATUS_SERIALIZATION_REPORT_RECEIVED_NAME;
        } else if ($this->serialization_codes_sent_date) {
            return self::STATUS_SERIALIZATION_CODES_SENT_NAME;
        } else if ($this->serialization_codes_request_date) {
            return self::STATUS_SERIALIZATION_CODES_REQUESTED_NAME;
        }

        return Order::STATUS_PRODUCTION_IS_STARTED_NAME;
    }

    public function getCanBePreparedForShippingFromManufacturerAttribute(): bool
    {
        return $this->productionInvoices()
            ->whereIn('payment_type_id', [
                InvoicePaymentType::FULL_PAYMENT_ID,
                InvoicePaymentType::FINAL_PAYMENT_ID,
            ])
            ->whereNotNull('sent_for_payment_date')
            ->exists();
    }

    public function getCanBeMarkedAsReadyForShipmentFromManufacturerAttribute(): bool
    {
        return $this->production_is_finished
            && !is_null($this->packing_list_file);
    }

    public function getShipmentFromManufacturerStartedAttribute(): bool
    {
        return !is_null($this->shipment_from_manufacturer_start_date);
    }

    public function getDeliveryToWarehouseRequestedAttribute(): bool
    {
        return !is_null($this->delivery_to_warehouse_request_date);
    }

    public function getCanRequestDeliveryToWarehouseAttribute(): bool
    {
        return !$this->delivery_to_warehouse_requested
            && $this->shipment_from_manufacturer_started;
    }

    public function getShipmentFromManufacturerEndedAttribute(): bool
    {
        return !is_null($this->shipment_from_manufacturer_end_date);
    }

    public function getArrivedAtWarehouseAttribute(): bool
    {
        return !is_null($this->warehouse_arrival_date);
    }

    public function getCanEndShipmentFromManufacturerAttribute(): bool
    {
        return !$this->shipment_from_manufacturer_ended
            && $this->shipment_from_manufacturer_started
            && !is_null($this->delivery_to_warehouse_loading_confirmed_date);
    }

    public function getTotalQuantityOfAllBatchesAttribute(): int
    {
        return $this->batches->count()
            ? $this->batches->sum('quantity')
            : 0;
    }

    public function getTotalFactualQuantityOfAllBatchesAttribute(): int
    {
        return $this->batches->count()
            ? $this->batches->sum('factual_quantity')
            : 0;
    }

    public function getTotalDefectsQuantityOfAllBatchesAttribute(): int
    {
        return $this->batches->count()
            ? $this->batches->sum('defects_quantity')
            : 0;
    }

    public function getRemainingQuantityForBatchesAttribute(): int
    {
        return $this->factual_quantity - $this->total_quantity_of_all_batches;
    }

    public function getCanCreateBatchAttribute(): bool
    {
        return $this->remaining_quantity_for_batches > 0;
    }

    public function getPackingListAssetUrlAttribute(): string
    {
        return asset(self::PACKING_LIST_FILE_PATH . '/' . $this->packing_list_file);
    }

    public function getPackingListFilePathAttribute()
    {
        return public_path(self::PACKING_LIST_FILE_PATH . '/' . $this->packing_list_file);
    }

    public function getCoaAssetUrlAttribute(): string
    {
        return asset(self::COA_FILE_PATH . '/' . $this->coa_file);
    }

    public function getCoaFilePathAttribute()
    {
        return public_path(self::COA_FILE_PATH . '/' . $this->coa_file);
    }

    public function getCooAssetUrlAttribute(): string
    {
        return asset(self::COO_FILE_PATH . '/' . $this->coo_file);
    }

    public function getCooFilePathAttribute()
    {
        return public_path(self::COO_FILE_PATH . '/' . $this->coo_file);
    }

    public function getDeclarationForEuropeAssetUrlAttribute(): string
    {
        return asset(self::DECLARATION_FOR_EUROPE_FILE_PATH . '/' . $this->declaration_for_europe_file);
    }

    public function getDeclarationForEuropeFilePathAttribute()
    {
        return public_path(self::DECLARATION_FOR_EUROPE_FILE_PATH . '/' . $this->declaration_for_europe_file);
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

        static::forceDeleting(function ($record) {
            // Detach 'productionInvoices'
            $record->productionInvoices()->detach();

            // Delete hasOne 'deliveryToWarehouse' and 'Export' relations
            $invoices = Invoice::where('order_product_id', $record->id)
                ->where('order_id', $record->order_id)
                ->get();

            foreach ($invoices as $invoice) {
                $invoice->delete();
            }

            // Delete batches
            foreach ($record->batches as $batch) {
                $batch->delete();
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
            'lastComment',
            'serializationType',

            'order' => function ($orderQuery) {
                $orderQuery->with([ // ->withBasicRelations not used because of redundant 'lastComment'
                    'country',
                    'currency',

                    'manufacturer' => function ($manufacturersQuery) {
                        $manufacturersQuery->select(
                            'manufacturers.id',
                            'manufacturers.name',
                            'manufacturers.country_id',
                            'bdm_user_id',
                        )
                            ->with([
                                'bdm:id,name,photo',
                                'country',
                            ]);
                    },
                ]);
            },

            'process' => function ($processQuery) {
                $processQuery->withRelationsForOrderProduct()
                    ->withOnlyRequiredSelectsForOrderProduct();
            },

            'productionInvoices' => function ($invoicesQuery) {
                $invoicesQuery->with([
                    'paymentType',
                ]);
            },
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'batches',
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

    public function scopeOnlyProductionIsStarted($query)
    {
        return $query->whereHas('order', function ($orderQuery) {
            $orderQuery->onlyProductionIsStarted();
        });
    }

    public function scopeOnlyReadyForShipmentFromManufacturer($query)
    {
        return $query->whereNotNull('readiness_for_shipment_from_manufacturer_date');
    }

    public function scopeOnlyWithInvoicesSentForPayment($query)
    {
        return $query->whereHas('order', function ($orderQuery) {
            $orderQuery->onlyWithInvoicesSentForPayment();
        });
    }

    public function scopeOnlySerializedByManufacturer($query)
    {
        $serializationTypeId = SerializationType::findByName(SerializationType::BY_MANUFACTURER_TYPE_NAME)->id;

        return $query->where('serialization_type_id', $serializationTypeId);
    }

    public function scopeOnlySerializedByUs($query)
    {
        $serializationTypeId = SerializationType::findByName(SerializationType::BY_US_TYPE_NAME)->id;

        return $query->where('serialization_type_id', $serializationTypeId);
    }

    public function scopeOnlyArrivedAtWarehouse($query)
    {
        return $query->whereNotNull('warehouse_arrival_date');
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

    /**
     * Add 'order_production_start_date' attribute.
     *
     * Used while ordering products by 'order_production_start_date'
     */
    public function scopeWithOrderProductionStartDateAttribute($query)
    {
        return $query
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->selectRaw('orders.production_start_date as order_production_start_date');
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    /**
     * Implement method defined in BaseModel abstract class.
     *
     * Used by 'PLPD', 'CDM', 'DD', 'MSD', 'ELD' departments.
     *
     * Special breadcrumbs for 'MSD'.
     */
    public function generateBreadcrumbs($department = null): array
    {
        // Special breadcrumbs for 'MSD'.
        if ($department == 'MSD') {
            $lowercasedDepartment = strtolower($department);

            // Generate index page breadcrumb omptions
            if ($this->serializationType->name == SerializationType::BY_MANUFACTURER_TYPE_NAME) {
                $indexPageLink = route('msd.order-products.serialized-by-manufacturer.index');
                $indexPageTitle = __('Factory');
            }

            return [
                ['link' => $indexPageLink, 'text' => $indexPageTitle],
                ['link' => url()->current(), 'text' => $this->title],
            ];
        }

        // If department is declared
        if ($department) {
            $lowercasedDepartment = strtolower($department);

            return [
                ...$this->order->generateBreadcrumbs($department),
                ['link' => route($lowercasedDepartment . '.order-products.index'), 'text' => __('Products')],
                ['link' => route($lowercasedDepartment . '.order-products.edit', $this->id), 'text' => $this->title],
            ];
        }

        // If department is null
        return [
            ['link' => null, 'text' => __('Orders')],
            ['link' => null, 'text' => __('Products')],
            ['link' => null, 'text' => $this->title],
        ];
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return $this->process->trademark_en ?: ('#' . $this->id);
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

            'relationInAmbiguous' => [
                [
                    'name' => 'order',
                    'attribute' => 'order_name',
                    'ambiguousAttribute' => 'orders.name',
                ],
            ],
        ];
    }

    /**
     * Applies a status-based filter to the OrderProduct query.
     *
     * Some filters are delegated to Order model filter.
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

        $filters = [
            Order::STATUS_PRODUCTION_IS_STARTED_NAME => fn($q) =>
            $q->whereHas(
                'order',
                fn($oq) =>
                $oq->whereNotNull('production_start_date')
            )
                ->whereNull('production_end_date'),

            self::STATUS_PRODUCTION_IS_FINISHED_NAME => fn($q) =>
            $q->whereNotNull('production_end_date')
                ->whereNull('readiness_for_shipment_from_manufacturer_date'),

            self::STATUS_IS_READY_FOR_SHIPMENT_FROM_MANUFACTURER_NAME => fn($q) =>
            $q->whereNotNull('readiness_for_shipment_from_manufacturer_date'),
        ];

        $filter = $filters[$status] ?? null;

        if ($filter) {
            $filter($query);
        } else {
            // Delegate filter to Order model
            $query->whereHas(
                'order',
                fn($orderQuery) =>
                Order::applyStatusFilter($orderQuery, $request)
            );
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

    public static function addDefaultDDQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_DD_ORDER_BY',
            'DEFAULT_DD_ORDER_TYPE',
            'DEFAULT_DD_PAGINATION_LIMIT',
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

    public static function addDefaultMSDSerializedByUsQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_MSD_SERIALIZED_BY_US_ORDER_BY',
            'DEFAULT_MSD_SERIALIZED_BY_US_ORDER_TYPE',
            'DEFAULT_MSD_SERIALIZED_BY_US_PAGINATION_LIMIT',
        );
    }

    public static function addDefaultELDQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_ELD_ORDER_BY',
            'DEFAULT_ELD_ORDER_TYPE',
            'DEFAULT_ELD_PAGINATION_LIMIT',
        );
    }

    public static function addDefaultWarehouseQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_WAREHOUSE_ORDER_BY',
            'DEFAULT_WAREHOUSE_ORDER_TYPE',
            'DEFAULT_WAREHOUSE_PAGINATION_LIMIT',
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

        // Upload files
        $this->uploadFile('packing_list_file', public_path(self::PACKING_LIST_FILE_PATH));
        $this->uploadFile('coa_file', public_path(self::COA_FILE_PATH));
        $this->uploadFile('coo_file', public_path(self::COO_FILE_PATH));
        $this->uploadFile('declaration_for_europe_file', public_path(self::DECLARATION_FOR_EUROPE_FILE_PATH));
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

    // MSD part
    function updateByMSDFromRequest($request)
    {
        $this->update($request->all());

        // HasMany relations
        $this->storeCommentFromRequest($request);
    }

    // ELD part
    function updateByELDFromRequest($request)
    {
        $this->update($request->all());

        // HasMany relations
        $this->storeCommentFromRequest($request);
    }

    // Warehouse part
    function updateFromWarehouseRequest($request)
    {
        $this->update($request->all());

        // HasMany relations
        $this->storeCommentFromRequest($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute togglings
    |--------------------------------------------------------------------------
    */

    /**
     * CMD BDM marks production process as finished
     */
    public function toggleProductionIsFinishedAttribute(Request $request)
    {
        $action = $request->input('action');

        if ($action == 'finish' && !$this->production_is_finished) {
            $this->production_end_date = now();
            $this->save();
        }
    }

    /**
     * CMD BDM marks product as ready for shipment
     */
    public function toggleIsReadyForShipmentFromManufacturerAttribute(Request $request)
    {
        $action = $request->input('action');

        if ($action == 'prepare' && !$this->is_ready_for_shipment_from_manufacturer) {
            $this->readiness_for_shipment_from_manufacturer_date = now();
            $this->save();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public function canAttachNewProductionInvoice(): bool
    {
        return $this->canAttachProductionInvoiceOFPrepaymentType()
            || $this->canAttachProductionInvoiceOfFinalPaymentType()
            || $this->canAttachProductionInvoiceOfFullPaymentType();
    }

    public function canAttachProductionInvoiceOFPrepaymentType(): bool
    {
        return $this->productionInvoices->count() == 0;
    }

    public function canAttachProductionInvoiceOfFinalPaymentType(): bool
    {
        return $this->productionInvoices
            ->where('payment_type_id', InvoicePaymentType::PREPAYMENT_ID)
            ->count() > 0

            && $this->productionInvoices
            ->where('payment_type_id', InvoicePaymentType::FINAL_PAYMENT_ID)
            ->count() == 0;
    }

    public function canAttachProductionInvoiceOfFullPaymentType(): bool
    {
        return $this->productionInvoices->count() == 0;
    }

    public function hasDeliveryToWarehouseInvoice(): bool
    {
        if ($this->relationLoaded('deliveryToWarehouseInvoice')) {
            return $this->deliveryToWarehouseInvoice != null;
        }

        return $this->deliveryToWarehouseInvoice()->exists();
    }

    public function canAddDeliveryToWarehouseInvoice(): bool
    {
        return !$this->hasDeliveryToWarehouseInvoice()
            && $this->shipment_from_manufacturer_ended;
    }

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

        if ($request->input('order_by') == 'order_production_start_date') {
            $query->withOrderProductionStartDateAttribute();
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
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Order', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 112, 'visible' => 1],
            ['name' => 'Price', 'order' => $order++, 'width' => 70, 'visible' => 1],
            ['name' => 'Currency', 'order' => $order++, 'width' => 84, 'visible' => 1],
            ['name' => 'Total price', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Status', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Serialization type', 'order' => $order++, 'width' => 156, 'visible' => 1],
            ['name' => 'Production status', 'order' => $order++, 'width' => 160, 'visible' => 1],
            ['name' => 'Layout approved date', 'order' => $order++, 'width' => 170, 'visible' => 1],
            ['name' => 'Prepayment completion date', 'order' => $order++, 'width' => 216, 'visible' => 1],
            ['name' => 'Production end date', 'order' => $order++, 'width' => 218, 'visible' => 1],
            ['name' => 'Final payment request date', 'order' => $order++, 'width' => 236, 'visible' => 1], // sent_for_payment_date (by BDM)
            ['name' => 'Final payment completion date', 'order' => $order++, 'width' => 264, 'visible' => 1],
            ['name' => 'Ready for shipment', 'order' => $order++, 'width' => 160, 'visible' => 1],
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
            ['name' => 'Status', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Order', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Invoices', 'order' => $order++, 'width' => 120, 'visible' => 1],
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

            ['name' => 'Production start date', 'order' => $order++, 'width' => 212, 'visible' => 1],
            ['name' => 'Production status', 'order' => $order++, 'width' => 160, 'visible' => 1],
            ['name' => 'Production end date', 'order' => $order++, 'width' => 270, 'visible' => 1],

            ['name' => 'Packing list', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'COA', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'COO', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Declaration for EUR1', 'order' => $order++, 'width' => 170, 'visible' => 1],
            ['name' => 'Ready for shipment', 'order' => $order++, 'width' => 160, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }

    public static function getDefaultDDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-DD-order-products')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-DD-order-products')) {
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

    public static function getDefaultPRDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-PRD-order-products')) {
            return null;
        }

        $order = 1;
        $columns = array();

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'BDM', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Status', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Order', 'order' => $order++, 'width' => 128, 'visible' => 1],
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

    public static function getDefaultMSDSerializedByManufacturerTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-MSD-order-products')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-MSD-order-products')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'Status', 'order' => $order++, 'width' => 144, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 112, 'visible' => 1],
            ['name' => 'Production end date', 'order' => $order++, 'width' => 236, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],

            ['name' => 'Serialization codes request date', 'order' => $order++, 'width' => 262, 'visible' => 1],
            ['name' => 'Serialization codes sent', 'order' => $order++, 'width' => 254, 'visible' => 1],
            ['name' => 'Serialization report received', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Report sent to hub', 'order' => $order++, 'width' => 184, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }

    public static function getDefaultELDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-ELD-order-products')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-ELD-order-products')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'Status', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 112, 'visible' => 1],
            ['name' => 'PO date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'PO №', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],

            ['name' => 'Packing list', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'COA', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'COO', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Declaration for EUR1', 'order' => $order++, 'width' => 170, 'visible' => 1],
            ['name' => 'Ready for shipment', 'order' => $order++, 'width' => 130, 'visible' => 1],

            ['name' => 'Shipment from manufacturer start date', 'order' => $order++, 'width' => 230, 'visible' => 1],
            ['name' => 'Shipment ID', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Manufacturer country', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Volume', 'order' => $order++, 'width' => 80, 'visible' => 1],
            ['name' => 'Packs', 'order' => $order++, 'width' => 90, 'visible' => 1],
            ['name' => 'Method of shipment', 'order' => $order++, 'width' => 124, 'visible' => 1],
            ['name' => 'Destination', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Transportation request', 'order' => $order++, 'width' => 204, 'visible' => 1],
            ['name' => 'Rate approved', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Forwarder', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Rate', 'order' => $order++, 'width' => 90, 'visible' => 1],
            ['name' => 'Currency', 'order' => $order++, 'width' => 84, 'visible' => 1],
            ['name' => 'Loading confirmed', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Shipment from manufacturer end date', 'order' => $order++, 'width' => 250, 'visible' => 1],

            ['name' => 'Invoice', 'order' => $order++, 'width' => 124, 'visible' => 1],
            ['name' => 'Arrived at warehouse', 'order' => $order++, 'width' => 124, 'visible' => 1],

            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }

    public static function getDefaultWarehouseTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-warehouse-products')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-warehouse-products')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'Status', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 112, 'visible' => 1],
            ['name' => 'PO date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'PO №', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],

            ['name' => 'Arrived at warehouse', 'order' => $order++, 'width' => 124, 'visible' => 1],
            ['name' => 'Original invoice', 'order' => $order++, 'width' => 166, 'visible' => 1],
            ['name' => 'Seller', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Customs code', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Factual quantity', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Batches', 'order' => $order++, 'width' => 206, 'visible' => 1],
            ['name' => 'Sum of batches', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Number of boxes (full)', 'order' => $order++, 'width' => 212, 'visible' => 1],
            ['name' => 'Number of packages in box (full)', 'order' => $order++, 'width' => 260, 'visible' => 1],
            ['name' => 'Defects quantity', 'order' => $order++, 'width' => 170, 'visible' => 1],

            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }
}
