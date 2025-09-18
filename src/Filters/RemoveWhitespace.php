<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

/**
 * Removes all whitespace characters from a string.
 *
 * @implements Filter<mixed, mixed>
 */
final readonly class RemoveWhitespace implements Filter
{
    /**
     * Removes all whitespace from a string.
     *
     * @param  mixed  $value  The value to process.
     * @param  array<int, mixed>  $options  (Unused)
     * @return mixed The processed string or the original value if not a string.
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        return preg_replace(
            pattern: '/\s+/',
            replacement: '',
            subject: $value
        );
    }
}
