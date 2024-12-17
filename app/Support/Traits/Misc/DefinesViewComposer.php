<?php

namespace App\Support\Traits\Misc;

use Illuminate\Support\Facades\View;

/**
 * Trait DefinesViewComposer
 *
 * Provides a method to define view composer
 *
 * @package App\Support\Traits\Model
 */
trait DefinesViewComposer
{
    private static function defineViewComposer($views, array $data)
    {
        View::composer($views, function ($view) use ($data) {
            $view->with($data);
        });
    }
}
