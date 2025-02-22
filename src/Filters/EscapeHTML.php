<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class EscapeHTML implements Filter
{
    /**
     * Escape HTML special characters in the given string.
     *
     * This filter converts special characters (e.g., <, >, &, ") to their HTML entities
     * using htmlspecialchars. If the input is a string, it’s escaped; non-string inputs
     * are returned unchanged.
     *
     * @param mixed $value The value to escape (typically a string)
     * @param array<string, mixed> $options Optional filter options (currently unused)
     * @return mixed The escaped string or original value if not a string
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        return is_string($value) ? htmlspecialchars($value) : $value;
    }
}
