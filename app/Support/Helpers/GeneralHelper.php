<?php

namespace App\Support\Helpers;

use Illuminate\Http\Request;

class GeneralHelper
{
    /**
     * Removes a key from an array if it exists.
     *
     * @param array $array
     * @param string $key
     * @return void
     */
    public static function removeArrayKey(array &$array, string $key): void
    {
        if (array_key_exists($key, $array)) {
            unset($array[$key]);
        }
    }

    /**
     * Get an array of boolean options represented by StdClass objects.
     *
     * Mainly used by radio groups.
     *
     * @return array
     */
    public static function getBooleanOptionsArray()
    {
        return [
            (object) ['caption' => trans('Да'), 'value' => 1],
            (object) ['caption' => trans('Нет'), 'value' => 0],
        ];
    }
}
