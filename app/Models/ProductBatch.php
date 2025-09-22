<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\Commentable;
use App\Support\Traits\Model\FormatsAttributeForDateTimeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductBatch extends BaseModel implements HasTitle
{
    use FormatsAttributeForDateTimeInput;
    use Commentable;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    // Warehouse
    const SETTINGS_WAREHOUSE_TABLE_COLUMNS_KEY = 'warehouse_product_batches_table_columns';
    const DEFAULT_ORDER_BY = 'updated_at';
    const DEFAULT_ORDER_TYPE = 'desc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    // MSD
    const SETTINGS_MSD_TABLE_COLUMNS_KEY = 'MSD_product_batches_table_columns';
    const DEFAULT_MSD_ORDER_BY = 'updated_at';
    const DEFAULT_MSD_ORDER_TYPE = 'desc';
    const DEFAULT_MSD_PAGINATION_LIMIT = 50;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    protected $casts = [
        'manufacturing_date' => 'date',
        'expiration_date' => 'date',
        'serialization_request_date' => 'datetime',
        'serialization_start_date' => 'date',
        'serialization_end_date' => 'date',
        'serialization_codes_request_date' => 'datetime',
        'serialization_codes_sent_date' => 'datetime',
        'serialization_report_recieved_date' => 'datetime',
        'report_sent_to_hub_date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function product()
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id')->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    public function getSerializationRequestedAttribute(): bool
    {
        return !is_null($this->serialization_request_date);
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
            'product',
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'comments'
        ]);
    }

    public function scopeOnlySerializationRequested($query)
    {
        return $query->whereNotNull('serialization_request_date');
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    /**
     * Implement method defined in BaseModel abstract class.
     */
    public function generateBreadcrumbs($department = null): array
    {
        return [
            ['link' => null, 'text' => __('Warehouse')],
            ['link' => route('warehouse.product-batches.index'), 'text' => __('Batches')],
            ['link' => null, 'text' => $this->title],
        ];
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return $this->series;
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
            'whereEqual' => [],
            'whereIn' => ['id', 'order_product_id'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Create & Update
    |--------------------------------------------------------------------------
    */

    public static function createFromWarehouseRequest($request)
    {
        self::create($request->all());
    }

    public function updateFromWarehouseRequest($request)
    {
        $this->update($request->all());
    }

    public function updateByMSDFromRequest($request)
    {
        $this->update($request->all());
    }

    /*
    |--------------------------------------------------------------------------
    | Adding default query params to request
    |--------------------------------------------------------------------------
    */

    public static function addDefaultMSDQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_MSD_ORDER_BY',
            'DEFAULT_MSD_ORDER_TYPE',
            'DEFAULT_MSD_PAGINATION_LIMIT',
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public static function getDefaultWarehouseTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-warehouse-product-batches')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-warehouse-product-batches')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'Series', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Manufacturing date', 'order' => $order++, 'width' => 144, 'visible' => 1],
            ['name' => 'Expiration date', 'order' => $order++, 'width' => 122, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 120, 'visible' => 1],

            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Brand Rus', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            ['name' => 'Factual quantity', 'order' => $order++, 'width' => 182, 'visible' => 1],
            ['name' => 'PO date', 'order' => $order++, 'width' => 116, 'visible' => 1],
            ['name' => 'PO №', 'order' => $order++, 'width' => 128, 'visible' => 1],
            ['name' => 'Arrived at warehouse', 'order' => $order++, 'width' => 124, 'visible' => 1],

            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
        );

        // Push serialization columns for PLPD Logistician
        if (Gate::forUser($user)->allows('request-serialization-for-product-batches')) {
            array_push(
                $columns,
                ['name' => 'Serialization request date', 'order' => $order++, 'width' => 202, 'visible' => 1],
                ['name' => 'Number of boxes', 'order' => $order++, 'width' => 152, 'visible' => 1],
                ['name' => 'Number of packages in box', 'order' => $order++, 'width' => 198, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 164, 'visible' => 1],
        );

        return $columns;
    }

    public static function getDefaultMSDTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-MSD-product-batches')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-MSD-product-batches')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'Series', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Manufacturing date', 'order' => $order++, 'width' => 144, 'visible' => 1],
            ['name' => 'Expiration date', 'order' => $order++, 'width' => 122, 'visible' => 1],
            ['name' => 'Quantity', 'order' => $order++, 'width' => 120, 'visible' => 1],

            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],

            ['name' => 'Start date of work', 'order' => $order++, 'width' => 170, 'visible' => 1],
            ['name' => 'Factual quantity', 'order' => $order++, 'width' => 182, 'visible' => 1],
            ['name' => 'Defects quantity', 'order' => $order++, 'width' => 170, 'visible' => 1],
            ['name' => 'End date of work', 'order' => $order++, 'width' => 172, 'visible' => 1],

            ['name' => 'Serialization codes request date', 'order' => $order++, 'width' => 248, 'visible' => 1],
            ['name' => 'Serialization codes sent', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Serialization report received', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'Report sent to hub', 'order' => $order++, 'width' => 184, 'visible' => 1],

            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],
        );

        return $columns;
    }
}
