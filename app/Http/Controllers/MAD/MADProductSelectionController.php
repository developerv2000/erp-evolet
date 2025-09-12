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

    public function exportAsExcel(Request $request)
    {
        $model = ModelHelper::addFullNamespaceToModelBasename($request->input('model'));

        // Preapare request for valid model querying
        $model::addRefererQueryParamsToRequest($request);
        $model::addDefaultQueryParamsToRequest($request);

        // Get finalized records query
        $query = $model::withRelationsForProductSelection();
        $filteredQuery = $model::filterQueryForRequest($query, $request);

        // Add joins if joined ordering requested
        if (method_exists($model, 'addJoinsForOrdering')) {
            $filteredQuery = $model::addJoinsForOrdering($filteredQuery, $request);
        }

        $finalizedQuery = $model::finalizeQueryForRequest($filteredQuery, $request, 'query');

        // Generate excel file
        $filepath = self::generateExcelFileFromQuery($finalizedQuery, $model);

        // Return download response
        return response()->download($filepath);
    }

    private static function generateExcelFileFromQuery($query, $model)
    {
        // Load Excel template
        $templatePath = storage_path(self::STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT);
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Collect all records
        $records = collect();
        $query->chunk(400, function ($chunked) use (&$records) {
            $records = $records->merge($chunked);
        });

        // Prepare records before export
        self::loadRecordsMatchedProductSearches($records);

        // Get additional country names
        $additionalCountries = self::insertAdditionalCountriesIntoSheet($sheet, $records);

        // insert records into sheet
        self::fillSheetWithRecords($sheet, $records, $model, $additionalCountries);

        // Save modified spreadsheet
        $filepath = self::saveSpreadsheet($spreadsheet);

        return $filepath;
    }

    private static function loadRecordsMatchedProductSearches($records)
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

    private static function insertAdditionalCountriesIntoSheet($sheet, $records)
    {
        $additionalCountries = self::getAdditionalCountries($records);

        // insert additional country titles between last default country and ZONE 4B columns
        $lastCountryColumnLetter = self::LAST_DEFAULT_COUNTRY_COLUMN_LETTER;
        $lastCountryColumnIndex = Coordinate::columnIndexFromString($lastCountryColumnLetter);

        foreach ($additionalCountries as $country) {
            // Insert new country column
            $nextColumnIndex = $lastCountryColumnIndex + 1;
            $nextColumnLetter = Coordinate::stringFromColumnIndex($nextColumnIndex);
            $sheet->insertNewColumnBefore($nextColumnLetter, 1);

            $insertedColumnIndex = $nextColumnIndex;
            $insertedColumnLetter = $nextColumnLetter;
            $insertedCellCoordinates = [$insertedColumnIndex, self::TITLES_ROW];
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

    private static function getAdditionalCountries($records)
    {
        // Collect unique additional countries
        $uniqueCountries = $records->flatMap->loaded_matched_product_searches->pluck('country.code')->unique();

        // Remove countries which already present in default countries
        $additionalCountries = $uniqueCountries->diff(self::DEFAULT_COUNTRIES);

        return $additionalCountries;
    }

    private static function fillSheetWithRecords($sheet, $records, $model, $additionalCountries)
    {
        // Join default and additional countries
        $allCountries = collect(self::DEFAULT_COUNTRIES)->merge($additionalCountries);

        // Get model basename
        $modelBaseName = basename($model);

        // Start records insert
        $row = self::RECORDS_INSERT_START_ROW;
        $recordsCounter = 1;

        // Loop through records
        foreach ($records as $record) {
            // Begin from 'A' column
            $columnIndex = 1;

            // Insert record counter
            $sheet->setCellValue([$columnIndex++, $row], $recordsCounter);

            // Get record column values, which are different for 'Product' and 'Process' models:
            // Form, Dosage, Pack, MOQ, Shelf life, Price, Target price, Agreed price, Currency
            $columnValues = self::getRecordColumnValues($record, $model);

            // Insert record column values (from 'B' to 'K')
            foreach ($columnValues as $value) {
                $sheet->setCellValue([$columnIndex++, $row], $value);
            }

            // Initialize dependencies
            $firstCountryColumnLetter = self::FIRST_DEFAULT_COUNTRY_COLUMN_LETTER;  // Reset value for each row
            $firstCountryColumnIndex = Coordinate::columnIndexFromString($firstCountryColumnLetter);
            $countryColumnIndexCounter = $firstCountryColumnIndex; // Used only for looping

            // Loop through all countries
            foreach ($allCountries as $country) {
                // Get country cell index (like 4L, 4M, etc) and its style
                $countryCellIndex = [$countryColumnIndexCounter, $row];
                $cellStyle = $sheet->getCell($countryCellIndex)->getStyle();

                // Mark country as matched and highlight background color
                if ($record->loaded_matched_product_searches->contains('country.code', $country)) {
                    // Set 1 for 'Product' and status name for 'Process' models
                    $countryValue = $modelBaseName == 'Product' ? 1 : $record->status->name;
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

        self::removeRedundantRow($sheet, $records, $row);
    }

    private static function getRecordColumnValues($record, $model)
    {
        switch ($model) {
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

    private static function removeRedundantRow($sheet, $records, $row)
    {
        // Remove last inserted redundant row
        if ($records->isNotEmpty()) {
            $sheet->removeRow($row);
        }
    }

    private static function saveSpreadsheet($spreadsheet)
    {
        // Create a writer and generate a unique filename for the export
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = date('Y-m-d H-i-s') . '.xlsx';
        $filename = FileHelper::ensureUniqueFilename($filename, storage_path(self::STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES));
        $filePath = storage_path(self::STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES  . '/' . $filename);

        // Save the Excel file
        $writer->save($filePath);

        return $filePath;
    }
}
