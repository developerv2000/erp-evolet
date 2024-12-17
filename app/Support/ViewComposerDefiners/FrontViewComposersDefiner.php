<?php

namespace App\Support\ViewComposerDefiners;

use App\Models\DailyPost;
use Illuminate\Support\Facades\View;

class FrontViewComposersDefiner
{
    public static function defineAll()
    {
        // Rightbar
        View::composer('front.layouts.rightbar', function ($view) {
            $view->with('todaysPost', DailyPost::getLatestRecord());
        });

        // Account leftbar
        View::composer('front.leftbars.account', function ($view) {
            $view->with('user', auth()->user());
        });
    }
}
