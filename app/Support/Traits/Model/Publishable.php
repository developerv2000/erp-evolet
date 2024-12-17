<?php

namespace App\Support\Traits\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Publishable
 *
 * Provides a scope to filter and optionally sort only published records based on the `publish_at` timestamp.
 *
 * @package App\Support\Traits
 */
trait Publishable
{
    /**
     * Scope a query to only include published records.
     *
     * Filters records where the `publish_at` timestamp is less than or equal to the current time.
     * Optionally, sorts the results by the `publish_at` timestamp in the specified direction.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @param string|null $sortType The sorting direction (e.g., 'asc', 'desc'). If `null`, no sorting is applied.
     * @return Builder The modified query builder instance.
     */
    public function scopeOnlyPublished(Builder $query, ?string $sortType = null): Builder
    {
        // Filter by publish date
        $query->where('publish_at', '<=', Carbon::now());

        // Apply sorting if a sort type is provided
        if ($sortType) {
            $query->orderBy('publish_at', $sortType);
        }

        return $query;
    }
}
