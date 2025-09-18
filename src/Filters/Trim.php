<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

/**
 * Removes leading and trailing whitespace from a string.
 *
 * @implements Filter<mixed, mixed>
 */
final readonly class Trim implements Filter
{
    /**
     * Removes whitespace from the beginning and end of a string.
     *
     * @param  mixed  $value  The value to trim.
     * @param  array<int, mixed>  $options  (Unused)
     * @return mixed The trimmed string or the original value if not a string.
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        return is_string($value) ? trim($value) : $value;
    }
}
