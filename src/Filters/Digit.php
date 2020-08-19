<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class Digit implements Filter
{
    /**
     *  Get only digit characters from the string.
     *
     * @param string $value
     * @param array $options
     * @return string
     */
    public function apply($value, array $options = [])
    {
        return preg_replace('/[^0-9]/si', '', $value);
    }
}
