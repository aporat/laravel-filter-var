<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class Digit implements Filter
{
    /**
     *  Get only digit characters from the string.
     *
     * @param mixed $value
     * @param array $options
     *
     * @return string|array|null
     */
    public function apply(mixed $value, array $options = []): string|array|null
    {
        return preg_replace('/[^0-9]/si', '', $value);
    }
}
