<?php

namespace App\Support\Abstracts;

use App\Support\Contracts\Model\Breadcrumbable;
use App\Support\Traits\Model\AddsDefaultQueryParamsToRequest;
use App\Support\Traits\Model\AddsRefererQueryParamsToRequest;
use App\Support\Traits\Model\FinalizesQueryForRequest;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model implements Breadcrumbable
{
    use AddsDefaultQueryParamsToRequest;
    use AddsRefererQueryParamsToRequest;
    use FinalizesQueryForRequest;
}
