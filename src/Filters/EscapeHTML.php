<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class EscapeHTML implements Filter
{
    /**
     *  Remove HTML tags and encode special characters from the given string.
     *
     * @param mixed $value
     * @param array $options
     *
     * @return mixed
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        return is_string($value) ? htmlspecialchars($value) : $value;
    }
}
