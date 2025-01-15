<?php

namespace App\Support\Helpers;

use Illuminate\Support\Str;
use InvalidArgumentException;

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
            (object) ['caption' => trans('Yes'), 'value' => 1],
            (object) ['caption' => trans('No'), 'value' => 0],
        ];
    }

    /**
     * Truncate a string to the specified length and append '...' if necessary.
     *
     * @param string $value The string to be truncated.
     * @param int $length The desired length of the truncated string.
     * @return string The truncated string with '...' appended if it exceeds the length.
     */
    public static function truncateString(string $value, int $length): string
    {
        if (mb_strlen($value) <= $length) {
            return $value;
        }

        return mb_substr($value, 0, $length) . '...';
    }

    /**
     * Convert a numeric price into a formatted string representation.
     *
     * @param float|int $price The numeric price to format.
     * @return string The formatted price string.
     */
    public static function formatPrice($price)
    {
        return number_format((int)$price, 0, ',', ' ');
    }

    /**
     * Get plain text from string without HTML tags.
     */
    public function getPlainTextFromStr($string)
    {
        if (empty($string)) {
            return '';
        }

        return Str::of($string)
            // Add a space after each closing tag to prevent text from joining
            ->replaceMatches('/>(?!\s)/', '> ')
            // Strip HTML tags
            ->stripTags()
            // Decode HTML entities
            ->htmlspecialcharsDecode()
            // Replace multiple spaces with a single space
            ->replaceMatches('/\s+/', ' ')
            // Remove spaces before commas and dots
            ->replaceMatches('/\s+([,.])/', '$1')
            // Trim the result
            ->trim();
    }

    /*
    |--------------------------------------------------------------------------
    | Percentage calculations
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate the percentage of an amount.
     *
     * @param float $amount The total amount.
     * @param float $percentage The percentage to calculate.
     * @return float The calculated value.
     */
    public static function calculatePercentage(float $amount, float $percentage): float
    {
        return ($amount * $percentage) / 100;
    }

    /**
     * Calculate the percentage a value represents of a total.
     *
     * @param float $total The total amount (representing 100%).
     * @param float $value The value to calculate the percentage for.
     * @return float The percentage the value represents of the total.
     */
    public static function calculatePercentageOfTotal(float $total, float $value): float
    {
        if ($total == 0) {
            throw new InvalidArgumentException("Total cannot be zero.");
        }
        return ($value / $total) * 100;
    }
}
