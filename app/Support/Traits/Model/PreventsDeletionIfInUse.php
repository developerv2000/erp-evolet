<?php

namespace App\Support\Traits\Model;

use Illuminate\Validation\ValidationException;

/**
 * Important: All models used in 'MiscModelController' must use this trait!
 */
trait PreventsDeletionIfInUse
{
    /**
     * Boot the trait and prevent deletion if the model is in use.
     */
    protected static function bootPreventsDeletionIfInUse()
    {
        static::deleting(function ($model) {
            // Ensure the model is fully loaded with related usage counts
            $loadedModel = static::withRelatedUsageCounts()->find($model->id);

            if ($loadedModel->usage_count > 0) {
                throw ValidationException::withMessages([
                    'record_deletion' => trans('validation.custom.misc_models.record_is_in_use', ['name' => $model->name ?: $model->id]),
                ]);
            }
        });
    }
}
