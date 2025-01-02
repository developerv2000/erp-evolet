<?php

namespace App\Support\Traits\Model;

trait RecalculatesAllUsageCounts
{
    /**
     * Recalculate the usage_count attributes for all records of this model.
     *
     * @return void
     */
    public static function recalculateAllUsageCounts(): void
    {
        static::query()->each(function ($instance) {
            $instance->recalculateUsageCount();
        });
    }
}
