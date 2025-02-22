<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class Lowercase implements Filter
{
    /**
     * Convert a string to lowercase using multibyte-safe encoding.
     *
     * This filter transforms all characters in the input string to lowercase using
     * mb_strtolower with UTF-8 encoding. If the input is not a string, it is returned
     * unchanged.
     *
     * @param mixed $value The value to convert (typically a string)
     * @param array<string, mixed> $options Optional filter options (currently unused)
     * @return mixed The lowercase string or original value if not a string
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        return is_string($value) ? mb_strtolower($value, 'UTF-8') : $value;
    }
}
