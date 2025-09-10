<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Models\User;
use App\Support\Helpers\ModelHelper;
use Illuminate\Support\Facades\View;

class GlobalViewComposersDefiner
{
    public static function defineAll()
    {
        self::definePaginationLimitComposer();
        self::defineDeletedUserImageComposer();
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

    private static function defineDeletedUserImageComposer()
    {
        View::composer('global.comments.partials.list', function ($view) {
            $view->with([
                'deletedUserImage' => User::getDeletedUserImage(),
            ]);
        });
    }
}
