<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class Digit implements Filter
{
    /**
     * Extract only digit characters from the input value.
     *
     * This filter removes all non-digit characters (anything not 0-9) from the input.
     * If the input is a string, it returns a string containing only digits. Non-string
     * inputs are implicitly converted to strings by preg_replace, and an empty string
     * is returned if no digits are present or if the input is null.
     *
     * @param  mixed  $value  The value to filter (typically a string)
     * @param  array<string, mixed>  $options  Optional filter options (currently unused)
     * @return string The filtered value containing only digits
     */
    public function apply(mixed $value, array $options = []): string
    {
        if ($value === null) {
            return '';
        }

        return preg_replace('/[^0-9]/si', '', $value);
    }
}
