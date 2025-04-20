<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class Uppercase implements Filter
{
    /**
     * Convert a string to uppercase using multibyte-safe encoding.
     *
     * This filter transforms all characters in the input string to uppercase using
     * mb_strtoupper with UTF-8 encoding by default. If the input is not a string, it
     * is returned unchanged.
     *
     * @param  mixed  $value  The value to convert (typically a string)
     * @param  array<int, mixed>  $options  Optional filter options (currently unused)
     * @return mixed The uppercase string or original value if not a string
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        return is_string($value) ? mb_strtoupper($value) : $value;
    }
}
