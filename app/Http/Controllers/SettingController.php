<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function updateLocale(Request $request)
    {
        $request->user()->updateSetting('locale', $request->locale);

        return redirect()->back();
    }

    public function toggleTheme(Request $request)
    {
        $user = $request->user();
        $reversedTheme = $user->settings['preferred_theme'] == 'light' ? 'dark' : 'light';
        $user->updateSetting('preferred_theme', $reversedTheme);

        return redirect()->back();
    }

    public function toggleLeftbar(Request $request)
    {
        $user = $request->user();
        $reversedLeftbar = !$user->settings['collapsed_leftbar'];
        $user->updateSetting('collapsed_leftbar', $reversedLeftbar);

        return true;
    }

    /**
     * Update table columns including orders, widths, and visibility.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateTableColumns(Request $request, $key)
    {
        $user = $request->user();
        $settings = $user->settings;

        // Check if the key exists in user settings
        if (!isset($settings[$key])) {
            abort(404, 'Settings key not found');
        }

        $columns = collect($settings[$key]);

        // Update column only if it exists in user settings
        $requestColumns = collect($request->columns)->keyBy('name');
        $columns = $columns->map(function ($column) use ($requestColumns) {
            if ($requestColumns->has($column['name'])) {
                $requestColumn = $requestColumns->get($column['name']);
                $column['order'] = $requestColumn['order'];
                $column['width'] = $requestColumn['width'];
                $column['visible'] = $requestColumn['visible'];
            }
            return $column;
        });

        // Sort columns by order and update user settings
        $orderedColumns = $columns->sortBy('order')->values()->all();
        $user->updateSetting($key, $orderedColumns);

        return true;
    }
}
