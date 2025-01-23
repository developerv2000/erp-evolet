<?php

namespace App\Support\Traits\Model;

/**
 * Trait FormatsAttributeForDateTimeInput
 *
 * Provides a method to format model attributes for use in HTML datetime-local inputs.
 */
trait FormatsAttributeForDateTimeInput
{
    /**
     * Format a given model attribute to a datetime-local input format.
     *
     * The format used is 'Y-m-d\TH:i', which is compatible with the HTML5 datetime-local input type.
     *
     * @param string $attribute The name of the attribute to be formatted.
     * @return string|null The formatted datetime string or null if the attribute is not set.
     */
    public function formatForDateTimeInput(string $attribute): ?string
    {
        return isset($this->{$attribute}) ? $this->{$attribute}->format('Y-m-d\TH:i') : null;
    }
}
