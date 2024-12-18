<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function toggleTheme(Request $request)
    {
        // dd($request->user());
        $user = $request->user();
        $reversedTheme = $user->settings['preferred_theme'] == 'light' ? 'dark' : 'light';
        $user->updateSetting('preferred_theme', $reversedTheme);

        return redirect()->back();
    }

    public function toggleDashboardLeftbar(Request $request)
    {
        $user = $request->user();
        $reversedLeftbar = !$user->settings['collapsed_dashboard_leftbar'];
        $user->updateSetting('collapsed_dashboard_leftbar', $reversedLeftbar);

        return true;
    }
}
