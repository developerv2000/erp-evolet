<?php

namespace App\Support\Contracts\Model;

interface UsageCountable
{
    /**
     * Recalculate the usage_count attribute for this model instance.
     *
     * @return void
     */
    public function recalculateUsageCount(): void;

    /**
     * Recalculate the usage_count attributes for all records of this model.
     *
     * @return void
     */
    public static function recalculateAllUsageCounts(): void;
}
