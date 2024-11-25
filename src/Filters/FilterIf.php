<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class FilterIf implements Filter
{
    /**
     *  Checks if filters should run if there is value passed that matches.
     *
     * @param mixed $value
     * @param array $options
     *
     * @return mixed
     */
    public function apply($value, array $options = [])
    {
        return array_key_exists($options[0], $value) && $value[$options[0]] === $options[1];
    }
}
