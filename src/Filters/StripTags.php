<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class StripTags implements Filter
{
    /**
     *  Strip tags from the given string.
     *
     * @param mixed $value
     * @param array $options
     *
     * @return mixed
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        return is_string($value) ? strip_tags($value) : $value;
    }
}
