<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class Capitalize implements Filter
{
    /**
     * Capitalize a string value using title case, preserving non-string values.
     *
     * This filter converts the input to lowercase first, then applies title case
     * capitalization using multibyte-safe functions. Non-string inputs are returned unchanged.
     *
     * @param mixed $value The value to capitalize
     * @param array<string, mixed> $options Optional filter options (currently unused)
     * @return mixed The capitalized string or original value if not a string
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        $lowercase = mb_strtolower($value, 'UTF-8');
        return mb_convert_case($lowercase, MB_CASE_TITLE, 'UTF-8');
    }
}
