<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

/**
 * Removes HTML and PHP tags from a string.
 *
 * @implements Filter<mixed, mixed>
 */
final readonly class StripTags implements Filter
{
    /**
     * Removes all HTML and PHP tags from a string.
     *
     * @param  mixed  $value  The value to process.
     * @param  array<int, mixed>  $options  (Unused)
     * @return mixed The tag-stripped string or the original value if not a string.
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        return is_string($value) ? strip_tags($value) : $value;
    }
}
