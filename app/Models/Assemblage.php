<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\CanExportRecordsAsExcel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\Commentable;
use App\Support\Traits\Model\UploadsFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class Assemblage extends BaseModel implements HasTitle, CanExportRecordsAsExcel
{
    use Commentable;
    use UploadsFile;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    // Export
    const SETTINGS_EXPORT_TABLE_COLUMNS_KEY = 'export_assamblages_table_columns';
    const DEFAULT_EXPORT_ORDER_BY = 'id';
    const DEFAULT_EXPORT_ORDER_TYPE = 'asc';
    const DEFAULT_EXPORT_PAGINATION_LIMIT = 50;

    // Files
    const INITIAL_ASSEMBLY_FILE_PATH = 'private/assemblages/initial-assemblies';
    const FINAL_ASSEMBLY_FILE_PATH = 'private/assemblages/final-assemblies';
    const COO_FILE_PATH = 'private/assemblages/COOs';
    const EURO_1_FILE_PATH = 'private/assemblages/euro-1';
    const GMP_OR_ISO_FILE_PATH = 'private/assemblages/gmp-or-iso';

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    protected $casts = [
        'assemblage_date' => 'date',
        'assembly_request_date' => 'datetime',
        'assembly_request_accepted_date' => 'datetime',
        'initial_assembly_acceptance_date' => 'date',
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
            ->withPivot(['quantity_for_assembly', 'additional_comment']);
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

    public function getCanRequestDeliveryToDestinationCountryAttribute(): bool
    {
        return !is_null($this->initial_assembly_acceptance_date)
            && !is_null($this->final_assembly_acceptance_date);
    }

    public function getCanEndShipmentFromWarehouseAttribute(): bool
    {
        return !$this->shipment_from_warehouse_end_date
            && $this->delivery_to_destination_country_request_date
            && !is_null($this->delivery_to_destination_country_loading_confirmed_date);
    }

    public function getInitialAssemblyAssetUrlAttribute(): string
    {
        return asset(self::INITIAL_ASSEMBLY_FILE_PATH . '/' . $this->initial_assembly_file);
    }

    public function getInitialAssemblyFilePathAttribute()
    {
        return public_path(self::INITIAL_ASSEMBLY_FILE_PATH . '/' . $this->initial_assembly_file);
    }

    public function getFinalAssemblyAssetUrlAttribute(): string
    {
        return asset(self::FINAL_ASSEMBLY_FILE_PATH . '/' . $this->final_assembly_file);
    }

    public function getFinalAssemblyFilePathAttribute()
    {
        return public_path(self::FINAL_ASSEMBLY_FILE_PATH . '/' . $this->final_assembly_file);
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
        return asset(self::EURO_1_FILE_PATH . '/' . $this->euro_1_file);
    }

    public function getDeclarationForEuropeFilePathAttribute()
    {
        return public_path(self::EURO_1_FILE_PATH . '/' . $this->euro_1_file);
    }

    public function getGmpOrIsoAssetUrlAttribute(): string
    {
        return asset(self::GMP_OR_ISO_FILE_PATH . '/' . $this->gmp_or_iso_file);
    }

    public function getGmpOrIsoFilePathAttribute()
    {
        return public_path(self::GMP_OR_ISO_FILE_PATH . '/' . $this->gmp_or_iso_file);
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
            'batches',
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
            'whereEqual' => ['number'],
            'whereIn' => ['id', 'country_id', 'shipment_type_id'],

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
    public static function createMultipleFromExportRequest($request)
    {
        $record = self::create($request->only([
            'number',
            'assemblage_date',
            'shipment_type_id',
            'country_id',
        ]));

        // HasMany relations
        $record->storeCommentFromRequest($request);

        // Store batches
        $batches = $request->input('batches', []);

        foreach ($batches as $batch) {
            // Skip if batch is finished
            $productBatch = ProductBatch::find($batch['batch_id']);
            if (!$productBatch || !$productBatch->is_unfinished) {
                continue;
            }

            // Attach batch
            $record->batches()->attach(
                $batch['batch_id'],
                [
                    'quantity_for_assembly' => $batch['quantity_for_assembly'],
                    'additional_comment' => isset($batch['additional_comment']) ? $batch['additional_comment'] : null,
                ]
            );
        }
    }

    // PLPD or ELD
    public function updateFromExportRequest($request)
    {
        $this->update($request->all());

        // HasMany relations
        $this->storeCommentFromRequest($request);

        // Upload files
        $this->uploadFile('initial_assembly_file', public_path(self::INITIAL_ASSEMBLY_FILE_PATH));
        $this->uploadFile('final_assembly_file', public_path(self::FINAL_ASSEMBLY_FILE_PATH));
        $this->uploadFile('coo_file', public_path(self::COO_FILE_PATH));
        $this->uploadFile('euro_1_file', public_path(self::EURO_1_FILE_PATH));
        $this->uploadFile('gmp_or_iso_file', public_path(self::GMP_OR_ISO_FILE_PATH));
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
            ['name' => 'Batches', 'order' => $order++, 'width' => 84, 'visible' => 1],
            ['name' => 'Assembly request date', 'order' => $order++, 'width' => 164, 'visible' => 1],

            ['name' => 'Comments', 'order' => $order++, 'width' => 132, 'visible' => 1],
            ['name' => 'Last comment', 'order' => $order++, 'width' => 240, 'visible' => 1],

            ['name' => 'Request accept date', 'order' => $order++, 'width' => 168, 'visible' => 1],
            ['name' => 'Initial assembly date', 'order' => $order++, 'width' => 172, 'visible' => 1],
            ['name' => 'Initial assembly', 'order' => $order++, 'width' => 136, 'visible' => 1],
            ['name' => 'Final assembly date', 'order' => $order++, 'width' => 176, 'visible' => 1],
            ['name' => 'Final assembly', 'order' => $order++, 'width' => 140, 'visible' => 1],
            ['name' => 'Documents provision date', 'order' => $order++, 'width' => 240, 'visible' => 1],
            ['name' => 'COO date', 'order' => $order++, 'width' => 96, 'visible' => 1],
            ['name' => 'COO', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'EURO 1 date', 'order' => $order++, 'width' => 96, 'visible' => 1],
            ['name' => 'EURO 1', 'order' => $order++, 'width' => 120, 'visible' => 1],
            ['name' => 'GMP or ISO', 'order' => $order++, 'width' => 96, 'visible' => 1],
            ['name' => 'Volume', 'order' => $order++, 'width' => 80, 'visible' => 1],
            ['name' => 'Packs', 'order' => $order++, 'width' => 90, 'visible' => 1],

            ['name' => 'Transportation request', 'order' => $order++, 'width' => 204, 'visible' => 1],
            ['name' => 'Rate approved', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Forwarder', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Rate', 'order' => $order++, 'width' => 90, 'visible' => 1],
            ['name' => 'Currency', 'order' => $order++, 'width' => 84, 'visible' => 1],
            ['name' => 'Loading confirmed', 'order' => $order++, 'width' => 180, 'visible' => 1],
            ['name' => 'Shipment from warehouse end date', 'order' => $order++, 'width' => 250, 'visible' => 1],

            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'ID', 'order' => $order++, 'width' => 62, 'visible' => 1],
        );

        return $columns;
    }
}
