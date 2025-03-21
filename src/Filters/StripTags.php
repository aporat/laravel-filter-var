<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class StripTags implements Filter
{
    /**
     * Remove HTML and PHP tags from a string.
     *
     * This filter uses strip_tags to eliminate all HTML and PHP tags from the input string,
     * leaving only plain text. If the input is not a string, it is returned unchanged.
     *
     * @param mixed                $value   The value to process (typically a string)
     * @param array<string, mixed> $options Optional filter options (currently unused)
     *
     * @return mixed The tag-stripped string or original value if not a string
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        return is_string($value) ? strip_tags($value) : $value;
    }
}
