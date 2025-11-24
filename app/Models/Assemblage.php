<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\CanExportRecordsAsExcel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\Commentable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class Assemblage extends BaseModel implements HasTitle, CanExportRecordsAsExcel
{
    use Commentable;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    // Export
    const SETTINGS_EXPORT_TABLE_COLUMNS_KEY = 'export_table_columns';
    const DEFAULT_EXPORT_ORDER_BY = 'id';
    const DEFAULT_EXPORT_ORDER_TYPE = 'asc';
    const DEFAULT_EXPORT_PAGINATION_LIMIT = 50;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    protected $casts = [
        'application_date' => 'date',
        'request_sent_date' => 'datetime',
        'request_accepted_date' => 'datetime',
        'inital_assembly_acceptance_date' => 'date',
        'final_assembly_acceptance_date' => 'date',
        'documents_provision_date_to_warehouse' => 'date',
        'coo_file_date' => 'date',
        'euro_1_file_date' => 'date',
        'delivery_to_destination_country_request_date' => 'datetime',
        'delivery_to_destination_country_rate_approved_date' => 'date',
        'delivery_to_destination_country_loading_confirmed_date' => 'date',
        'shipment_from_warehouse_end_date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function batches()
    {
        return $this->belongsToMany(ProductBatch::class)
            ->withPivot('quantity_for_assembly');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class)->withTrashed();
    }

    public function shipmentType()
    {
        return $this->belongsTo(ShipmentType::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'delivery_to_destination_country_currency_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    public function getIsReadyForShipmentFromWarehouseAttribute(): bool
    {
        return !is_null($this->inital_assembly_acceptance_date)
            && !is_null($this->final_assembly_acceptance_date);
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::deleting(function ($record) {
            foreach ($record->invoices as $invoice) {
                $invoice->delete();
            }

            $record->batches()->detach();
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
            'shipmentType',
            'country',
            'currency',
            'lastComment',
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

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    /**
     * Implement method defined in BaseModel abstract class.
     *
     * Used by 'PLPD' and 'ELD' departments.
     */
    public function generateBreadcrumbs($department = null): array
    {
        return [
            ['link' => route('export.assemblages.index'), 'text' => __('Assemblages')],
            ['link' => route('export.assemblages.edit', $this->id), 'text' => $this->title],
        ];
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return $this->number;
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

        return $query;
    }

    private static function getFilterConfig(): array
    {
        return [
            'whereIn' => ['id', 'number', 'country_id', 'shipment_type_id'],

            'dateRange' => [
                'created_at',
                'updated_at'
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Adding default query params to request
    |--------------------------------------------------------------------------
    */

    public static function addDefaultExportQueryParamsToRequest(Request $request)
    {
        self::addDefaultQueryParamsToRequest(
            $request,
            'DEFAULT_EXPORT_ORDER_BY',
            'DEFAULT_EXPORT_ORDER_TYPE',
            'DEFAULT_EXPORT_PAGINATION_LIMIT',
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
            $newProduct = $record->products()->create([
                'process_id' => $product['process_id'],
                'quantity' => $product['quantity'],
                'serialization_type_id' => $product['serialization_type_id'],
            ]);

            // Store product comments
            if (isset($product['comment']) && $product['comment']) {
                $newProduct->comments()->create([
                    'body' => '<p>' . $product['comment'] . '</p>',
                    'user_id' => auth()->user()->id,
                ]);
            }
        }
    }

    public function updateByPLPDFromRequest($request)
    {
        $this->update($request->all());

        // HasMany relations
        $this->storeCommentFromRequest($request);
    }

    // ELD part

    public function updateByELDFromRequest($request)
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
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute togglings
    |--------------------------------------------------------------------------
    */
    /**
     * PLPD Logistician sents request to ELD
     */
    public function toggleRequestSentAttribute(Request $request)
    {
        $action = $request->input('action');

        if ($action == 'sent' && !$this->request_sent_date) {
            $this->request_sent_date = now();
            $this->save();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public static function getDefaultExportTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-export-assemblages')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-export-assemblages')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'Assemblage №', 'order' => $order++, 'width' => 114, 'visible' => 1],
            ['name' => 'Assemblage date', 'order' => $order++, 'width' => 110, 'visible' => 1],
            ['name' => 'Method of shipment', 'order' => $order++, 'width' => 124, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 64, 'visible' => 1],
            // ['name' => 'Manufacturer', 'order' => $order++, 'width' => 140, 'visible' => 1],
            // ['name' => 'Brand Eng', 'order' => $order++, 'width' => 150, 'visible' => 1],
            // ['name' => 'MAH', 'order' => $order++, 'width' => 102, 'visible' => 1],
            // ['name' => 'Series', 'order' => $order++, 'width' => 100, 'visible' => 1],
            // ['name' => 'Manufacturing date', 'order' => $order++, 'width' => 144, 'visible' => 1],
            // ['name' => 'Expiration date', 'order' => $order++, 'width' => 122, 'visible' => 1],
            // ['name' => 'Batch quantity', 'order' => $order++, 'width' => 150, 'visible' => 1],
            // ['name' => 'Quantity for assembly', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'Products', 'order' => $order++, 'width' => 100, 'visible' => 1],
            ['name' => 'Assemblage request date', 'order' => $order++, 'width' => 160, 'visible' => 1],

            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
        );

        return $columns;
    }
}
