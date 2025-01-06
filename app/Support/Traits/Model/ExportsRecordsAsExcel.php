<?php

namespace App\Support\Traits\Model;

use App\Support\Contracts\Model\PreparesFetchedRecordsForExport;
use App\Support\Helpers\FileHelper;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Trait ExportsRecordsAsExcel
 *
 * Provides functionality to export model records to an Excel file using a predefined template.
 * The trait supports exporting records for both privileged users and non privileged users, where privileged users
 * can export all records using chunking and non-privileged users are limited to a specified number of records.
 *
 * @package App\Support\Traits
 */
trait ExportsRecordsAsExcel
{
    /**
     * Export model records to an Excel file.
     *
     * Exports the provided records query to an Excel file using a predefined template.
     * Privileged users users will export the records in chunks to avoid memory issues,
     * while non-privileged users will only export a limited number of records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query containing the records to export.
     * @return \Illuminate\Http\Response The response to download the Excel file.
     */
    public static function exportRecordsAsExcel($query)
    {
        $priviligedUser = Gate::allows('export-unlimited-records-as-excel');

        // Load the Excel template
        $template = storage_path(static::STORAGE_PATH_OF_EXCEL_TEMPLATE_FILE_FOR_EXPORT);
        $spreadsheet = IOFactory::load($template);
        $sheet = $spreadsheet->getActiveSheet();

        // Export records based on user privileges
        if ($priviligedUser) {
            // Privileged users: process large record sets in chunks
            static::fillSheetByChunkingRecords($query, $sheet);
        } else {
            // Non-privileged users: limit records for export
            static::fillSheetByLimitedRecords($query, $sheet);
        }

        // Save and return the Excel file
        return static::saveAndDownloadExcel($spreadsheet);
    }

    /**
     * Fill the Excel sheet by chunking records (Privileged users).
     *
     * For privileged users, process records in chunks to avoid memory issues when dealing
     * with large datasets. Each chunk of records is loaded and written to the Excel sheet.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query to fetch records.
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet The Excel sheet to fill.
     * @return void
     */
    private static function fillSheetByChunkingRecords($query, &$sheet)
    {
        $columnIndex = 1;
        $row = 2;

        // Chunk the records to handle large datasets efficiently
        $query->chunk(800, function ($recordsChunk) use (&$sheet, &$columnIndex, &$row) {
            // Prepare records for export if necessary
            $nterfaces = class_implements(static::class);
            if (in_array(PreparesFetchedRecordsForExport::class, $nterfaces)) {
                static::prepareFetchedRecordsForExport($recordsChunk);
            }

            // Write each record to the Excel sheet
            foreach ($recordsChunk as $record) {
                $columnIndex = 1;
                $columnValues = $record->getExcelColumnValuesForExport();

                foreach ($columnValues as $value) {
                    $sheet->setCellValue([$columnIndex++, $row], $value);
                }

                // Move to the next row for the next record
                $row++;
            }
        });
    }

    /**
     * Fill the Excel sheet with a limited number of records (non-privileged users).
     *
     * For non-privileged users, limit the query to a specific number of records and
     * write those records to the Excel sheet.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query to fetch records.
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet The Excel sheet to fill.
     * @return void
     */
    private static function fillSheetByLimitedRecords($query, &$sheet)
    {
        $columnIndex = 1;
        $row = 2;

        // Limit the records query for non-privileged users
        $limitedRecords = $query->limit(static::LIMITED_EXCEL_RECORDS_COUNT_FOR_EXPORT)->get();

        // Prepare records for export if necessary
        $nterfaces = class_implements(static::class);
        if (in_array(PreparesFetchedRecordsForExport::class, $nterfaces)) {
            static::prepareFetchedRecordsForExport($recordsChunk);
        }

        // Write the limited records to the Excel sheet
        foreach ($limitedRecords as $record) {
            $columnIndex = 1;
            $columnValues = $record->getExcelColumnValuesForExport();

            foreach ($columnValues as $value) {
                $sheet->setCellValue([$columnIndex++, $row], $value);
            }

            // Move to the next row for the next record
            $row++;
        }
    }

    /**
     * Save the Excel file and return a download response.
     *
     * Saves the generated Excel file to the appropriate storage path and returns a response
     * that prompts the user to download the file.
     *
     * @param \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet The generated spreadsheet.
     * @return \Illuminate\Http\Response The response to download the Excel file.
     */
    private static function saveAndDownloadExcel($spreadsheet)
    {
        // Create a writer and generate a unique filename for the export
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = date('Y-m-d H-i-s') . '.xlsx';

        $filename = FileHelper::ensureUniqueFilename($filename, storage_path(static::STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES));
        $filePath = storage_path(static::STORAGE_PATH_FOR_EXPORTING_EXCEL_FILES . '/' . $filename);

        // Save the Excel file
        $writer->save($filePath);

        // Return a download response
        return response()->download($filePath);
    }
}
