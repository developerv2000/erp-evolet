<?php

namespace App\Support\Contracts\Model;

interface Breadcrumbable
{
    /**
     * Get the breadcrumb items for the model.
     *
     * Used in route breadcrumbs like 'model.edit ,'comments.index', 'attachments.index' etc.
     *
     * @return array
     */
    public function generateBreadcrumbs(): array;
}
