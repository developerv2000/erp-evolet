<?php

namespace App\Support\Helpers;

class ModelHelper
{
    /**
     * Adds the full namespace to the given model base name.
     *
     * @param string $basename
     * @param string|null $namespace
     * @return string
     */
    public static function addFullNamespaceToModelBasename($basename, $namespace = null)
    {
        // Default to 'App\Models' if no custom namespace is provided
        $namespace = $namespace ?? 'App\Models';

        // Ensure the namespace ends with a backslash
        if (substr($namespace, -1) !== '\\') {
            $namespace .= '\\';
        }

        return $namespace . $basename;
    }

    public static function getPaginationLimitOptions()
    {
        return [
            10,
            20,
            50,
            100,
            200,
            250,
            500,
        ];
    }
}
