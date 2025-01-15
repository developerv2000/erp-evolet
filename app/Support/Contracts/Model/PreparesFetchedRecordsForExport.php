<?php

namespace App\Support\Contracts\Model;

interface PreparesFetchedRecordsForExport
{
    /**
     * Prepare fetched records by loading necessary relations etc. for better performance
     *
     * @return void
     */
    public static function prepareFetchedRecordsForExport($records);
}
