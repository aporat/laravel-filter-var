<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

/**
 * Escapes HTML special characters in a string for safe output.
 *
 * @implements Filter<mixed, mixed>
 */
final readonly class EscapeHTML implements Filter
{
    /**
     * Escapes HTML special characters in a string.
     *
     * @param  mixed  $value  The value to escape.
     * @param  array<int, mixed>  $options  (Unused)
     * @return mixed The escaped string or the original value if not a string.
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        return htmlspecialchars(
            string: $value,
            flags: ENT_QUOTES | ENT_HTML5,
            encoding: 'UTF-8',
            double_encode: false
        );
    }
}
