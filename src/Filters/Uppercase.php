<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class Uppercase implements Filter
{
    /**
     *  Lowercase the given string.
     *
     * @param mixed $value
     * @param array $options
     *
     * @return mixed
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        return is_string($value) ? mb_strtoupper($value) : $value;
    }
}
