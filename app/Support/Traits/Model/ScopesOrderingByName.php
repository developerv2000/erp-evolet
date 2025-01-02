<?php

namespace App\Support\Traits\Model;

trait ScopesOrderingByName
{
    /**
     * Scope a query to order results by the 'name' column.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByName($query, $direction = 'asc')
    {
        return $query->orderBy('name', $direction);
    }
}
