<?php

namespace App\Support\Abstracts;

use App\Support\Traits\Model\AddsDefaultQueryParamsToRequest;
use App\Support\Traits\Model\FinalizesQueryForRequest;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use AddsDefaultQueryParamsToRequest;
    use FinalizesQueryForRequest;

    /**
     * Get the breadcrumb items for the model.
     *
     * Used in route breadcrumbs like 'model.edit ,'comments.index', 'attachments.index' etc.
     *
     * @return array
     */
    abstract public function generateBreadcrumbs(): array;
}
