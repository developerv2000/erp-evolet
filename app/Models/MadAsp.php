<?php

namespace App\Models;

use App\Support\Abstracts\BaseModel;
use App\Support\Contracts\Model\HasTitle;
use App\Support\Helpers\FileHelper;
use App\Support\Helpers\GeneralHelper;
use App\Support\Traits\Model\CalculatesMADASPQuarterAndYearCounts;
use App\Support\Traits\Model\Commentable;
use Illuminate\Database\Eloquent\Relations\Pivot;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MadAsp extends BaseModel implements HasTitle
{
    use Commentable;
    use CalculatesMADASPQuarterAndYearCounts;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const DEFAULT_ORDER_BY = 'year';
    const DEFAULT_ORDER_TYPE = 'desc';
    const DEFAULT_PAGINATION_LIMIT = 50;

    const STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT = 'app/excel/export-templates/mad-asp.xlsx';
    const STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES = 'app/excel/exports/mad-asp';

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    protected $guarded = ['id'];
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'mad_asp_country')
            ->orderBy('mad_asp_country.id');
    }

    public function MAHs()
    {
        return $this->belongsToMany(MarketingAuthorizationHolder::class, 'mad_asp_country_marketing_authorization_holder')
            ->withPivot(self::getPivotColumnNamesForMAHsRelation())
            ->orderBy('mad_asp_country_marketing_authorization_holder.id');
    }

    /**
     * Return marketing authorization holders for specific country
     */
    public function MAHsOfSpecificCountry($country)
    {
        return $this->MAHs()
            ->wherePivot('country_id', $country->id);
    }

    /**
     * Get already loaded MAHs filtered by specific country.
     */
    public function getLoadedMAHsForSpecificCountry($country)
    {
        return $this->MAHs->filter(function ($mah) use ($country) {
            return $mah->pivot->country_id == $country->id;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::deleting(function ($record) {
            // First remove MAHs
            $record->MAHs()->detach();

            // Then remove countries
            $record->countries()->detach();
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
        ]);
    }

    public function scopeWithBasicRelationCounts($query)
    {
        return $query->withCount([
            'comments',
            'countries',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    */

    // Implement method defined in BaseModel abstract class
    public function generateBreadcrumbs(): array
    {
        return [
            ['link' => route('mad.asp.index'), 'text' => __('SPG')],
            ['link' => route('mad.asp.edit', $this->year), 'text' => $this->title],
        ];
    }

    // Implement method declared in HasTitle Interface
    public function getTitleAttribute(): string
    {
        return $this->year;
    }

    /*
    |--------------------------------------------------------------------------
    | Create & Update
    |--------------------------------------------------------------------------
    */

    public static function createFromRequest($request)
    {
        $record = self::create($request->all());
        $record->storeCommentFromRequest($request);

        // Attach countries
        $countryIDs = $request->input('country_ids', []);
        $defaultMAHs = self::getMAHIDsAttachedByDefaultOnCreate();

        foreach ($countryIDs as $country) {
            $record->countries()->attach($country);

            // Attach MAHs for each countries
            $record->MAHs()->attach($defaultMAHs, [
                'country_id' => $country,
            ]);
        }
    }

    public function updateFromRequest($request)
    {
        $this->update($request->all());
        $this->storeCommentFromRequest($request);
    }

    /**
     * Used on mad-asp.countries.store route
     */
    public function attachCountryOnCountryCreate($request)
    {
        // Attach country
        $countryID = $request->input('country_id');
        $this->countries()->attach($countryID);

        // Attach MAHs
        $mahIDs = $request->input('marketing_authorization_holder_ids');

        foreach ($mahIDs as $mah) {
            $this->MAHs()->attach($mah, ['country_id' => $countryID]);
        }
    }

    /**
     * Used on mad-asp.countries.destroy route
     */
    public function detachCountriesByID($countryIDs)
    {
        // Detach MAHs related to the given countries
        $this->MAHs()->wherePivotIn('country_id', $countryIDs)->detach();

        // Detach countries
        $this->countries()->detach($countryIDs);
    }

    /**
     * Used on mad-asp.mahs.store route
     */
    public function attachMAHOnMAHCreate($request)
    {
        $pivotData = $request->except(['marketing_authorization_holder_id', '_token', 'previous_url']);
        $mahID = $request->input('marketing_authorization_holder_id');

        $this->MAHs()->attach($mahID, $pivotData);
    }

    /**
     * Used on mad-asp.mahs.update route
     */
    public function updateMAHFromRequest($mah, $country, $request)
    {
        $pivotData = $request->except('_token', 'previous_url', '_method');

        $this->MAHsOfSpecificCountry($country)->updateExistingPivot($mah->id, $pivotData);
    }

    /**
     * Used on mad-asp.mahs.destroy route
     */
    public function detachCountryMAHsByID($country, $mahIDs)
    {
        $this->MAHsOfSpecificCountry($country)
            ->wherePivotIn('marketing_authorization_holder_id', $mahIDs)->detach();
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public static function getPivotColumnNamesForMAHsRelation(): array
    {
        $pivots = [
            'mad_asp_id',
            'country_id',
            'marketing_authorization_holder_id',
        ];

        $months = GeneralHelper::collectCalendarMonths();

        foreach ($months as $month) {
            $pivots[] = $month['name'] . '_europe_contract_plan';
            $pivots[] = $month['name'] . '_india_contract_plan';
        }

        return $pivots;
    }

    /**
     * Return marketing authorization holder IDs attached by default on create.
     */
    public static function getMAHIDsAttachedByDefaultOnCreate()
    {
        $names = ['S', 'B', 'V', 'L', 'N'];

        return MarketingAuthorizationHolder::whereIn('name', $names)
            ->orderByRaw("FIELD(name, '" . implode("','", $names) . "')")
            ->pluck('id');
    }

    public function attachAllCountryMAHs()
    {
        foreach ($this->countries as $country) {
            $country->MAHs = $this->getLoadedMAHsForSpecificCountry($country);
        }
    }

    public function attachAllCountryRequestedMAHs($request)
    {
        $requestedMAHIDs = $request->input('marketing_authorization_holder_id', []);

        foreach ($this->countries as $country) {
            // Get all MAHs
            $MAHs = $this->getLoadedMAHsForSpecificCountry($country);

            // Filter requested MAHs
            if (count($requestedMAHIDs)) {
                $MAHs = $MAHs->whereIn('id', $requestedMAHIDs);
            }

            $country->MAHs = $MAHs;
        }
    }

    /**
     * Return an array of display options for filter.
     *
     * Used on show pages filter.
     *
     * @return array
     */
    public static function getFilterDisplayOptions()
    {
        return [
            'Months',
            'Quarters',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Calculation functions
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate and load all calculations based on year.
     * Calculations must be done in below proper order to avoid errors:
     *
     * 1. Marketing authorization holders calculations of each countries.
     * 2. Country calculations.
     * 3. ASP summary calculations.
     */
    public function makeAllCalculations($request)
    {
        // Preapare record for calculations
        $this->loadRequestedCountriesForCalculations($request);
        $this->loadMAHsForCalculations($request);
        $this->addUnderDiscussionMAHForCountries($request);

        foreach ($this->countries as $country) {
            // Step 1: Marketing authorization holders calculations of each countries.
            foreach ($country->MAHs as $mah) {
                $mah->makeAllMadAspCalculations($this, $request);
            }

            // Step 2: Country calculations.
            $country->makeAllMadAspCalculations($this, $request);
        }

        // Step 3: ASP summary calculations.
        $this->summarizeSelfCalculations($request);
    }

    /**
     * Summarize se;f calculations after finishing all MAH and Country calculations
     */
    private function summarizeSelfCalculations($request)
    {
        // Perform monthly, quarterly, and yearly calculations
        $this->calculateSelfMonthlyProcessCounts();
        $this->calculateAspQuartersProcessCounts(); // CalculatesMADASPQuarterAndYearCounts trait
        $this->calculateAspYearProcessCounts(); // CalculatesMADASPQuarterAndYearCounts trait
        $this->calculateAspYearPercentages(); // CalculatesMADASPQuarterAndYearCounts trait
    }

    /**
     * Calculate the total 'contract_plan', 'contract_fact', and 'register_fact'
     * counts for each month based on related Countryies.
     *
     * This method sets the following properties for each month:
     * - 'month_contract_plan'
     * - 'month_contract_fact'
     * - 'month_register_fact'
     *
     * @return void
     */
    public function calculateSelfMonthlyProcessCounts()
    {
        $months = GeneralHelper::collectCalendarMonths();

        foreach ($months as $month) {
            $monthName = $month['name'];

            // Calculate totals for the current month based on Country codes
            [$contractPlanCount, $contractFactCount, $registerFactCount] = $this->sumMonthlyCountsOfCountries($monthName);

            // Assign totals to the current model instance
            $this->{$monthName . '_contract_plan'} = $contractPlanCount;
            $this->{$monthName . '_contract_fact'} = $contractFactCount;
            $this->{$monthName . '_register_fact'} = $registerFactCount;
        }
    }

    /**
     * Sum the 'contract_plan', 'contract_fact', and 'register_fact' counts for a specific month
     * across all related Countryies.
     *
     * @param  string  $monthName
     * @return array  [contractPlanCount, contractFactCount, registerFactCount]
     */
    private function sumMonthlyCountsOfCountries($monthName)
    {
        $contractPlanCount = 0;
        $contractFactCount = 0;
        $registerFactCount = 0;

        // Iterate through related Countries to accumulate monthly counts
        foreach ($this->countries as $country) {
            $contractPlanCount += $country->{$monthName . '_contract_plan'} ?? 0;
            $contractFactCount += $country->{$monthName . '_contract_fact'} ?? 0;
            $registerFactCount += $country->{$monthName . '_register_fact'} ?? 0;
        }

        return [$contractPlanCount, $contractFactCount, $registerFactCount];
    }

    /*
    |--------------------------------------------------------------------------
    | Preaparation for calculation functions
    |--------------------------------------------------------------------------
    */

    private function loadRequestedCountriesForCalculations($request)
    {
        $countryIDs = $request->input('country_ids');

        $this->load([
            'countries' => function ($query) use ($countryIDs) {
                if ($countryIDs) {
                    $query->whereIn('countries.id', $countryIDs);
                }
            }
        ]);
    }

    private function loadMAHsForCalculations($request)
    {
        $this->load(['MAHs']);
        $this->attachAllCountryRequestedMAHs($request);
    }

    private function addUnderDiscussionMAHForCountries($request)
    {
        $underDiscussionNamedMAH = self::getUnderDiscussionMAHForCalculations();

        // Return if underDiscussionMAH is absent in requested MAHs
        $requestedMAHIDs = $request->input('marketing_authorization_holder_id', []);
        if (count($requestedMAHIDs) && !in_array($underDiscussionNamedMAH->id, $requestedMAHIDs)) {
            return;
        }

        foreach ($this->countries as $country) {
            // Clone the pivot data for each country
            $pivotData = array_merge($underDiscussionNamedMAH->pivot->toArray(), [
                'country_id' => $country->id,
            ]);

            // Create a new Pivot instance
            $underDiscussionNamedMAHWithPivot = clone $underDiscussionNamedMAH;
            $underDiscussionNamedMAHWithPivot->setRelation(
                'pivot',
                new Pivot($pivotData, 'mad_asp_country_marketing_authorization_holder', true)
            );

            $country->MAHs->push($underDiscussionNamedMAHWithPivot);
        }
    }

    private function getUnderDiscussionMAHForCalculations()
    {
        $mah = MarketingAuthorizationHolder::getUnderDiscussionNamedRecord();
        $mah->name = MarketingAuthorizationHolder::UNDER_DISCUSSION_SHORT_NAME;

        $pivotData = [
            'marketing_authorization_holder_id' => $mah->id,
            'country_id' => 0, // Placeholder
            "January_europe_contract_plan" => 0,
            "January_india_contract_plan" => 0,
            "February_europe_contract_plan" => 0,
            "February_india_contract_plan" => 0,
            "March_europe_contract_plan" => 0,
            "March_india_contract_plan" => 0,
            "April_europe_contract_plan" => 0,
            "April_india_contract_plan" => 0,
            "May_europe_contract_plan" => 0,
            "May_india_contract_plan" => 0,
            "June_europe_contract_plan" => 0,
            "June_india_contract_plan" => 0,
            "July_europe_contract_plan" => 0,
            "July_india_contract_plan" => 0,
            "August_europe_contract_plan" => 0,
            "August_india_contract_plan" => 0,
            "September_europe_contract_plan" => 0,
            "September_india_contract_plan" => 0,
            "October_europe_contract_plan" => 0,
            "October_india_contract_plan" => 0,
            "November_europe_contract_plan" => 0,
            "November_india_contract_plan" => 0,
            "December_europe_contract_plan" => 0,
            "December_india_contract_plan" => 0,
        ];

        // Return an instance of the MAH with pivot data
        $mah->setRelation(
            'pivot',
            new Pivot($pivotData, 'mad_asp_country_marketing_authorization_holder', true)
        );

        return $mah;
    }

    /*
    |--------------------------------------------------------------------------
    | Export
    |--------------------------------------------------------------------------
    */

    public function exportAsExcel($request)
    {
        $months = GeneralHelper::collectCalendarMonths();
        $this->makeAllCalculations($request);

        $template = storage_path(self::STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT);
        $spreadsheet = IOFactory::load($template);
        $sheet = $spreadsheet->getActiveSheet();

        $columnIndex = 1;
        $row = 3;

        // Summary row
        $sheet->setCellValue([$columnIndex++, $row], $this->year);
        $columnIndex++; // empty (MAH) td

        // Summary year
        $sheet->setCellValue([$columnIndex++, $row], $this->year_contract_plan);
        $sheet->setCellValue([$columnIndex++, $row], $this->year_contract_fact);
        $sheet->setCellValue([$columnIndex++, $row], $this->year_contract_fact_percentage . ' %');
        $sheet->setCellValue([$columnIndex++, $row], $this->year_register_fact);
        $sheet->setCellValue([$columnIndex++, $row], $this->year_register_fact_percentage . ' %');

        // Summary Quarters 1 - 4
        for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++) {
            $sheet->setCellValue([$columnIndex++, $row], $this->{'quarter_' . $quarter . '_contract_plan'});
            $sheet->setCellValue([$columnIndex++, $row], $this->{'quarter_' . $quarter . '_contract_fact'});
            $sheet->setCellValue([$columnIndex++, $row], $this->{'quarter_' . $quarter . '_register_fact'});

            // Summary months 1 - 12
            for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++, $monthIndex++) {
                $sheet->setCellValue([$columnIndex++, $row], $this->{$months[$monthIndex]['name'] . '_contract_plan'});
                $sheet->setCellValue([$columnIndex++, $row], $this->{$months[$monthIndex]['name'] . '_contract_fact'});
                $sheet->setCellValue([$columnIndex++, $row], $this->{$months[$monthIndex]['name'] . '_register_fact'});
            }
        }
        // Summary row end
        $row++;

        // Country rows
        foreach ($this->countries as $country) {
            $row++; // Separate rows for reach countries
            $columnIndex = 1; // start from first column

            $sheet->setCellValue([$columnIndex++, $row], $country->code);
            $columnIndex++; // empty (MAH) td

            // Country year
            $sheet->setCellValue([$columnIndex++, $row], $country->year_contract_plan);
            $sheet->setCellValue([$columnIndex++, $row], $country->year_contract_fact);
            $sheet->setCellValue([$columnIndex++, $row], $country->year_contract_fact_percentage . ' %');
            $sheet->setCellValue([$columnIndex++, $row], $country->year_register_fact);
            $sheet->setCellValue([$columnIndex++, $row], $country->year_register_fact_percentage . ' %');

            // Country quarters 1 - 4
            for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++) {
                $sheet->setCellValue([$columnIndex++, $row], $country->{'quarter_' . $quarter . '_contract_plan'});
                $sheet->setCellValue([$columnIndex++, $row], $country->{'quarter_' . $quarter . '_contract_fact'});
                $sheet->setCellValue([$columnIndex++, $row], $country->{'quarter_' . $quarter . '_register_fact'});

                // Summary months 1 - 12
                for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++, $monthIndex++) {
                    $sheet->setCellValue([$columnIndex++, $row], $country->{$months[$monthIndex]['name'] . '_contract_plan'});
                    $sheet->setCellValue([$columnIndex++, $row], $country->{$months[$monthIndex]['name'] . '_contract_fact'});
                    $sheet->setCellValue([$columnIndex++, $row], $country->{$months[$monthIndex]['name'] . '_register_fact'});
                }
            }

            // MAH rows
            foreach ($country->MAHs as $mah) {
                $row++; // Separate rows for each MAHs
                $columnIndex = 1; // start from first column

                $sheet->setCellValue([$columnIndex++, $row], $country->code);
                $sheet->setCellValue([$columnIndex++, $row], $mah->name);

                // MAH year
                $sheet->setCellValue([$columnIndex++, $row], $mah->year_contract_plan);
                $sheet->setCellValue([$columnIndex++, $row], $mah->year_contract_fact);
                $sheet->setCellValue([$columnIndex++, $row], $mah->year_contract_fact_percentage . ' %');
                $sheet->setCellValue([$columnIndex++, $row], $mah->year_register_fact);
                $sheet->setCellValue([$columnIndex++, $row], $mah->year_register_fact_percentage . ' %');

                // MAH Quarters 1 - 4
                for ($quarter = 1, $monthIndex = 0; $quarter <= 4; $quarter++) {
                    $sheet->setCellValue([$columnIndex++, $row], $mah->{'quarter_' . $quarter . '_contract_plan'});
                    $sheet->setCellValue([$columnIndex++, $row], $mah->{'quarter_' . $quarter . '_contract_fact'});
                    $sheet->setCellValue([$columnIndex++, $row], $mah->{'quarter_' . $quarter . '_register_fact'});

                    // Summary months 1 - 12
                    for ($quarterMonths = 1; $quarterMonths <= 3; $quarterMonths++, $monthIndex++) {
                        $sheet->setCellValue([$columnIndex++, $row], $mah->{$months[$monthIndex]['name'] . '_contract_plan'});
                        $sheet->setCellValue([$columnIndex++, $row], $mah->{$months[$monthIndex]['name'] . '_contract_fact'});
                        $sheet->setCellValue([$columnIndex++, $row], $mah->{$months[$monthIndex]['name'] . '_register_fact'});
                    }
                }
            }

            $row++; // Separate country codes from each other by empty row
        }

        // Save file and return download response
        // Create a writer and generate a unique filename for the export
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'ASP ' . date('Y-m-d H-i-s') . '.xlsx';
        $filename = FileHelper::ensureUniqueFilename($filename, storage_path(static::STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES));
        $filePath = storage_path(static::STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES . '/' . $filename);

        // Save the Excel file
        $writer->save($filePath);

        // Return a download response
        return response()->download($filePath);
    }
}
