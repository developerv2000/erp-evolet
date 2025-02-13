<?php

namespace App\Support\Contracts\Model;

/**
 * Interface for models that require tracking usage counts.
 *
 * Important: All models used in 'MiscModelController' must implement this interface!
 */
interface TracksUsageCount
{
    /**
     * Scope: Eagerly load all related model counts for optimization.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelatedUsageCounts($query);

    /**
     * Get the total usage count by summing all related model references.
     *
     * @return int
     */
    public function getUsageCountAttribute();
}
