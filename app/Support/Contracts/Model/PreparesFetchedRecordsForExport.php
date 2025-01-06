<?php

namespace App\Support\Contracts\Model;

interface PreparesFetchedRecordsForExport
{
    /**
     * Prepare records by loading necessary relations etc. for better performance
     *
     * @return void
     */
    public static function prepareFetchedRecordsForExport($record);
}
