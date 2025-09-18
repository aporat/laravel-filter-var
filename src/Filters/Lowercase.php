<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

/**
 * Converts a string to lowercase using multibyte-safe functions.
 *
 * @implements Filter<mixed, mixed>
 */
final readonly class Lowercase implements Filter
{
    /**
     * Converts a string to lowercase.
     *
     * @param  mixed  $value  The value to convert.
     * @param  array<int, mixed>  $options  (Unused)
     * @return mixed The lowercase string or the original value if not a string.
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        return mb_strtolower(
            string: $value,
            encoding: 'UTF-8'
        );
    }
}
