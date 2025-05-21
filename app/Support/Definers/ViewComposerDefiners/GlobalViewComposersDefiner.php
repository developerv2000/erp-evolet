<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Support\Helpers\ModelHelper;
use Illuminate\Support\Facades\View;

class GlobalViewComposersDefiner
{
    public static function defineAll()
    {
        self::definePaginationLimitComposer();
    }

    /*
    |--------------------------------------------------------------------------
    | Definers
    |--------------------------------------------------------------------------
    */

    private static function definePaginationLimitComposer()
    {
        View::composer('components.filter.partials.pagination-limit-input', function ($view) {
            $view->with([
                'paginationLimitOptions' => ModelHelper::getPaginationLimitOptions(),
            ]);
        });
    }
}
