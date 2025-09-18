<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

/**
 * Converts a string to uppercase using multibyte-safe functions.
 *
 * @implements Filter<mixed, mixed>
 */
final readonly class Uppercase implements Filter
{
    /**
     * Converts a string to uppercase.
     *
     * @param  mixed  $value  The value to convert.
     * @param  array<int, mixed>  $options  (Unused)
     * @return mixed The uppercase string or the original value if not a string.
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        return mb_strtoupper(
            string: $value,
            encoding: 'UTF-8'
        );
    }
}
