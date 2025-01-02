<?php

namespace App\Support\Definers\ViewComposerDefiners;

use App\Support\Helpers\ModelHelper;
use App\Support\Traits\Misc\DefinesViewComposer;

class GlobalViewComposersDefiner
{
    use DefinesViewComposer;

    public static function defineAll()
    {
        self::definePaginationLimitComposer();
    }

    private static function definePaginationLimitComposer()
    {
        self::defineViewComposer('components.filter.partials.pagination-limit-input', [
            'paginationLimitOptions' => ModelHelper::getPaginationLimitOptions(),
        ]);
    }
}
