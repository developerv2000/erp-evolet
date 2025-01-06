<?php

namespace App\Support\Contracts\Model;

interface CanExportRecordsAsExcel
{
    /**
     * Add model query scoping with eager loaded relations and relations count  for export.
     *
     * @return void
     */
    public function scopeWithRelationsForExport($query);

    /**
     * Get Excel column values for exporting.
     *
     * This function returns an array containing the values of specific properties
     * of the current model instance, which are intended to be exported to an Excel file.
     *
     * @return array An array containing the Excel column values.
     */
    public function getExcelColumnValuesForExport(): array;
}
