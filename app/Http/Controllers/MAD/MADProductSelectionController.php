<?php

namespace App\Http\Controllers\MAD;

use App\Http\Controllers\Controller;
use App\Models\ProductSearchStatus;
use App\Support\Helpers\FileHelper;
use App\Support\Helpers\ModelHelper;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class MADProductSelectionController extends Controller
{
    const STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT = 'app/excel/export-templates/product-selection.xlsx';
    const STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES = 'app/excel/exports/product-selection';

    const DEFAULT_COUNTRIES = [
        'KZ',
        'TM',
        'KG',
        'AM',
        'TJ',
        'UZ',
        'GE',
        'MN',
        'RU',
        'AZ',
        'AL',
        'KE',
        'DO',
        'KH',
        'MM',
    ];

    const FIRST_DEFAULT_COUNTRY_COLUMN_LETTER = 'L';
    const LAST_DEFAULT_COUNTRY_COLUMN_LETTER = 'Z';
    const TITLES_ROW = 2;
    const RECORDS_INSERT_START_ROW = 4;

    private $model, $baseModel;

    public function exportAsExcel(Request $request)
    {
        $this->baseModel = $request->input('model');
        $this->model = ModelHelper::addFullNamespaceToModelBasename($this->baseModel);

        // Preapare request for valid model querying
        $this->model::addRefererQueryParamsToRequest($request);
        $this->model::addDefaultQueryParamsToRequest($request);

        // Get finalized records query
        $query = $this->model::withRelationsForProductSelection();
        $filteredQuery = $this->model::filterQueryForRequest($query, $request);

        // Add joins if joined ordering requested
        if (method_exists($this->model, 'addJoinsForOrdering')) {
            $filteredQuery = $this->model::addJoinsForOrdering($filteredQuery, $request);
        }

        $finalizedQuery = $this->model::finalizeQueryForRequest($filteredQuery, $request, 'query');

        // Generate excel file
        $filepath = $this::generateExcelFileFromQuery($finalizedQuery, $this->model, $this->baseModel);

        // Return download response
        return response()->download($filepath);
    }

    private function generateExcelFileFromQuery($query)
    {
        // Load Excel template
        $templatePath = storage_path($this::STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT);
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Collect all records
        $records = collect();
        $query->chunk(400, function ($chunked) use (&$records) {
            $records = $records->merge($chunked);
        });

        // Prepare 'Product' records before export
        if ($this->baseModel == 'Product') {
            $this::loadProductsMatchedProductSearches($records);
            $uniqueRecords = $records;
        }

        // Get only unique records by 'product_id' for 'Process' model
        if ($this->baseModel == 'Process') {
            $uniqueRecords = $records->unique('product_id');
        }

        // Get additional country names
        $additionalCountries = $this::insertAdditionalCountriesIntoSheet($sheet, $records, $this->baseModel);

        // insert records into sheet
        $this::fillSheetWithRecords($sheet, $records, $uniqueRecords, $this->model, $this->baseModel, $additionalCountries);

        // Save modified spreadsheet
        $filepath = $this::saveSpreadsheet($spreadsheet);

        return $filepath;
    }

    private function loadProductsMatchedProductSearches($records)
    {
        // Append matched ProductSearch`s manually, so it won`t load many times.
        // Append only active searches, skipping "canceled" ones.
        $canceledStatusID = ProductSearchStatus::getCanceledStatusID();

        $records->each(function ($record) use ($canceledStatusID) {
            $matchedRecords = $record->matched_product_searches;

            $activeMatchedRecords = $matchedRecords->filter(fn($record) => $record->status_id != $canceledStatusID);

            $record->loaded_matched_product_searches = $activeMatchedRecords;
        });
    }

    private function insertAdditionalCountriesIntoSheet($sheet, $records)
    {
        $additionalCountries = $this::getAdditionalCountries($records, $this->baseModel);

        // insert additional country titles between last default country and ZONE 4B columns
        $lastCountryColumnLetter = $this::LAST_DEFAULT_COUNTRY_COLUMN_LETTER;
        $lastCountryColumnIndex = Coordinate::columnIndexFromString($lastCountryColumnLetter);

        foreach ($additionalCountries as $country) {
            // Insert new country column
            $nextColumnIndex = $lastCountryColumnIndex + 1;
            $nextColumnLetter = Coordinate::stringFromColumnIndex($nextColumnIndex);
            $sheet->insertNewColumnBefore($nextColumnLetter, 1);

            $insertedColumnIndex = $nextColumnIndex;
            $insertedColumnLetter = $nextColumnLetter;
            $insertedCellCoordinates = [$insertedColumnIndex, $this::TITLES_ROW];
            $sheet->setCellValue($insertedCellCoordinates, $country);

            // Update cell styles
            $sheet->getColumnDimension($insertedColumnLetter)->setWidth(5);
            $cellStyle = $sheet->getCell($insertedCellCoordinates)->getStyle();
            $cellStyle->getFill()->getStartColor()->setARGB('00FFFF');
            $cellStyle->getFont()->setColor(new Color(Color::COLOR_BLACK));
            $lastCountryColumnIndex = $insertedColumnIndex;
        }

        return $additionalCountries;
    }

    private function getAdditionalCountries($records)
    {
        // Collect unique additional countries
        $uniqueCountries = $this->baseModel == 'Product'
            ? $records->flatMap->loaded_matched_product_searches->pluck('country.code')->unique()
            : $records->pluck('searchCountry.code')->unique(); // Else if 'Process'

        // Remove countries which already present in default countries
        $additionalCountries = $uniqueCountries->diff($this::DEFAULT_COUNTRIES);

        return $additionalCountries;
    }

    private function fillSheetWithRecords($sheet, $records, $uniqueRecords, $additionalCountries)
    {
        // Join default and additional countries
        $allCountries = collect($this::DEFAULT_COUNTRIES)->merge($additionalCountries);

        // Start records insert
        $row = $this::RECORDS_INSERT_START_ROW;
        $recordsCounter = 1;

        // Loop through records
        foreach ($uniqueRecords as $record) {
            // Begin from 'A' column
            $columnIndex = 1;

            // Insert record counter
            $sheet->setCellValue([$columnIndex++, $row], $recordsCounter);

            // Get record column values, which are different for 'Product' and 'Process' models:
            // Form, Dosage, Pack, MOQ, Shelf life, Price, Target price, Agreed price, Currency
            $columnValues = $this::getRecordColumnValues($record, $this->model);

            // Insert record column values (from 'B' to 'K')
            foreach ($columnValues as $value) {
                $sheet->setCellValue([$columnIndex++, $row], $value);
            }

            // Initialize dependencies
            $firstCountryColumnLetter = $this::FIRST_DEFAULT_COUNTRY_COLUMN_LETTER;  // Reset value for each row
            $firstCountryColumnIndex = Coordinate::columnIndexFromString($firstCountryColumnLetter);
            $countryColumnIndexCounter = $firstCountryColumnIndex; // Used only for looping

            // Loop through all countries
            foreach ($allCountries as $country) {
                // Get country cell index (like 4L, 4M, etc) and its style
                $countryCellIndex = [$countryColumnIndexCounter, $row];
                $cellStyle = $sheet->getCell($countryCellIndex)->getStyle();
                $countryValue = null;

                // Mark country as matched and highlight background color
                if ($this->baseModel == 'Product') {
                    if ($record->loaded_matched_product_searches->contains('country.code', $country)) {
                        $countryValue = 1;
                    }
                } else if ($this->baseModel == 'Process') {
                    $matched = $records
                        ->where('product_id', $record->product_id)
                        ->where('searchCountry.code', $country)
                        ->first();

                    if ($matched) {
                        $countryValue = $matched->status->name;
                    }
                }

                if ($countryValue) {
                    // Set 1 for 'Product' and status name for 'Process' models
                    $sheet->setCellValue($countryCellIndex, $countryValue);

                    // Update cell styles
                    $cellStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $cellStyle->getFill()->getStartColor()->setARGB('92D050');
                } else {
                    // Reset background color because new inserted rows copy previous row styles
                    $cellStyle->getFill()->getStartColor()->setARGB('FFFFFF');
                }

                $countryColumnIndexCounter++; // Move to the next country
            }

            $row++; // Move to the next row
            $recordsCounter++; // Increment record counter
            $sheet->insertNewRowBefore($row, 1);  // Insert new rows to escape rewriting default countries list
        }

        $this::removeRedundantRow($sheet, $records, $row);
    }

    private function getRecordColumnValues($record)
    {
        switch ($this->model) {
            case 'App\Models\Product':
                return [
                    $record->inn->name,
                    $record->form->name,
                    $record->dosage,
                    $record->pack,
                    $record->moq,
                    $record->shelfLife->name,
                ];
                break;

            case 'App\Models\Process':
                return [
                    $record->product->inn->name,
                    $record->product->form->name,
                    $record->product->dosage,
                    $record->product->pack,
                    $record->product->moq,
                    $record->product->shelfLife->name,
                    $record->manufacturer_first_offered_price,
                    null, // Skip 'Target price'
                    $record->agreed_price,
                    $record->currency?->name,
                ];
        }
    }

    private function removeRedundantRow($sheet, $records, $row)
    {
        // Remove last inserted redundant row
        if ($records->isNotEmpty()) {
            $sheet->removeRow($row);
        }
    }

    private function saveSpreadsheet($spreadsheet)
    {
        // Create a writer and generate a unique filename for the export
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = date('Y-m-d H-i-s') . '.xlsx';
        $filename = FileHelper::ensureUniqueFilename($filename, storage_path($this::STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES));
        $filePath = storage_path($this::STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES  . '/' . $filename);

        // Save the Excel file
        $writer->save($filePath);

        return $filePath;
    }
}
