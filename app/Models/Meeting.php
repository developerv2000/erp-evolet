<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\CanExportRecordsAsExcel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\GeneralHelper;
use App\Support\Helpers\QueryFilterHelper;
use App\Support\Traits\Model\ExportsRecordsAsExcel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;

class Meeting extends BaseModel implements HasTitle, CanExportRecordsAsExcel
{
    /** @use HasFactory<\Database\Factories\MeetingFactory> */
    use HasFactory;
    use SoftDeletes;
    use ExportsRecordsAsExcel;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const SETTINGS_MAD_TABLE_COLUMNS_KEY = 'MAD_Meetings_table_columns';

    const DEFAULT_ORDER_BY = 'updated_at';
    const DEFAULT_ORDER_TYPE = 'desc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    const LIMITED_EXCEL_RECORDS_COUNT_FOR_EXPORT = 50;
    const STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT = 'app/excel/export-templates/meetings.xlsx';
    const STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES = 'app/excel/exports/meetings';

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class)->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::restoring(function ($record) {
            if ($record->manufacturer->trashed()) {
                $record->manufacturer->restore();
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
            'manufacturer' => function ($manufacturersQuery) {
                $manufacturersQuery->select([
                    'id',
                    'name',
                    'country_id',
                    'analyst_user_id',
                    'bdm_user_id'
                ])

                    ->withOnly([
                        'country',

                        'analyst:id,name,photo',
                        'bdm:id,name,photo',
                    ]);
            },
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([]);
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    // Implement method defined in BaseModel abstract class
    public function generateBreadcrumbs($department = null): array
    {
        $breadcrumbs = [
            ['link' => route('mad.meetings.index'), 'text' => __('Meetings')],
        ];

        if ($this->trashed()) {
            $breadcrumbs[] = ['link' => route('mad.meetings.trash'), 'text' => __('Trash')];
        }

        $breadcrumbs[] = ['link' => route('mad.meetings.edit', $this->id), 'text' => $this->title];

        return $breadcrumbs;
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return $this->year . ' — ' . GeneralHelper::truncateString($this->manufacturer->name, 50);
    }

    // Implement method declared in CanExportRecordsAsExcel Interface
    public function scopeWithRelationsForExport($query)
    {
        return $query->withBasicRelations()
            ->withBasicRelationCounts();
    }

    // Implement method declared in CanExportRecordsAsExcel Interface
    public function getExcelColumnValuesForExport(): array
    {
        return [
            $this->id,
            $this->year,
            $this->manufacturer->name,
            $this->manufacturer->bdm->name,
            $this->manufacturer->analyst->name,
            $this->manufacturer->country->name,
            $this->who_met,
            $this->plan,
            $this->topic,
            $this->result,
            $this->outside_the_exhibition,
            $this->created_at,
            $this->updated_at,
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
            'whereEqual' => ['year'],
            'whereIn' => ['id', 'manufacturer_id'],
            'like' => ['who_met'],
            'dateRange' => ['created_at', 'updated_at'],

            'relationIn' => [
                [
                    'name' => 'manufacturer',
                    'attribute' => 'analyst_user_id',
                ],

                [
                    'name' => 'manufacturer',
                    'attribute' => 'bdm_user_id',
                ],

                [
                    'name' => 'manufacturer',
                    'attribute' => 'country_id',
                ],
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Create & Update
    |--------------------------------------------------------------------------
    */

    public static function createFromRequest($request)
    {
        self::create($request->all());
    }

    public function updateFromRequest($request)
    {
        $this->update($request->all());
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Provides the default MAD table columns along with their properties.
     *
     * These columns are typically used to display data in tables,
     * such as on index and trash pages, and are iterated over in a loop.
     *
     * @return array
     */
    public static function getDefaultMADTableColumnsForUser($user)
    {
        if (Gate::forUser($user)->denies('view-MAD-Meetings')) {
            return null;
        }

        $order = 1;
        $columns = array();

        if (Gate::forUser($user)->allows('edit-MAD-Meetings')) {
            array_push(
                $columns,
                ['name' => 'Edit', 'order' => $order++, 'width' => 40, 'visible' => 1],
            );
        }

        array_push(
            $columns,
            ['name' => 'Year', 'order' => $order++, 'width' => 56, 'visible' => 1],
            ['name' => 'Manufacturer', 'order' => $order++, 'width' => 160, 'visible' => 1],
            ['name' => 'BDM', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Analyst', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Country', 'order' => $order++, 'width' => 144, 'visible' => 1],
            ['name' => 'Who met', 'order' => $order++, 'width' => 142, 'visible' => 1],
            ['name' => 'Plan', 'order' => $order++, 'width' => 220, 'visible' => 1],
            ['name' => 'Topic', 'order' => $order++, 'width' => 220, 'visible' => 1],
            ['name' => 'Result', 'order' => $order++, 'width' => 340, 'visible' => 1],
            ['name' => 'Outside the exhibition', 'order' => $order++, 'width' => 220, 'visible' => 1],
            ['name' => 'Date of creation', 'order' => $order++, 'width' => 130, 'visible' => 1],
            ['name' => 'Update date', 'order' => $order++, 'width' => 150, 'visible' => 1],
            ['name' => 'ID', 'order' => $order++, 'width' => 70, 'visible' => 1],
        );

        return $columns;
    }
}
