<?php

namespace App\Support\Traits\Model;

/**
 * Trait GetsMinifiedRecordsWithName
 *
 * Provides a method to retrieve a minimal set of records including ID and name.
 */
trait GetsMinifiedRecordsWithName
{
    public static function scopeMinifiedRecordsWithName($query)
    {
        return $query
            ->select('id', 'name')
            ->withOnly([])
            ->orderBy('name', 'asc');
    }

    /**
     * Retrieve all records with minimal fields (ID and name), ordered by name.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getMinifiedRecordsWithName()
    {
        return self::select('id', 'name')
            ->withOnly([]) // Ensures no extra relations are loaded.
            ->orderBy('name', 'asc')
            ->get();
    }
}
