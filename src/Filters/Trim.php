<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class Trim implements Filter
{
    /**
     * Remove leading and trailing whitespace from a string.
     *
     * This filter uses trim to strip whitespace from the beginning and end of the input
     * string. If the input is not a string, it is returned unchanged.
     *
     * @param mixed $value The value to trim (typically a string)
     * @param array<string, mixed> $options Optional filter options (currently unused)
     * @return mixed The trimmed string or original value if not a string
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        return is_string($value) ? trim($value) : $value;
    }
}
