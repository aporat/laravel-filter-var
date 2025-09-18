<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

/**
 * Capitalizes a string value using title case.
 *
 * @implements Filter<mixed, mixed>
 */
final readonly class Capitalize implements Filter
{
    /**
     * Capitalizes a string, preserving non-string values.
     *
     * This filter uses multibyte-safe functions to apply title case
     * capitalization. Non-string inputs are returned unchanged.
     *
     * @param  mixed  $value  The value to capitalize.
     * @param  array<int, mixed>  $options  (Unused)
     * @return mixed The capitalized string or original value.
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        return mb_convert_case(
            string: mb_strtolower(
                string: $value,
                encoding: 'UTF-8'
            ),
            mode: MB_CASE_TITLE,
            encoding: 'UTF-8'
        );
    }
}
