<?php

namespace App\Support\Contracts\Model;

interface HasTitle
{
    /**
     * Get the title attribute of the model.
     *
     * Only models without 'title' attribute should implement this interface.
     * Used in route breadcrumbs like 'comments.index', 'attachments.index' etc.
     *
     * @return string
     */
    public function getTitleAttribute(): string;
}
