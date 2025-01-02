<?php

namespace App\Support\Traits\Model;

trait ScopesOrderingByUsageCount
{
    /**
     * Scope a query to order results by the 'usage_count' column.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByUsageCount($query, $direction = 'desc')
    {
        return $query->orderBy('usage_count', $direction);
    }
}
