<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class RemoveWhitespace implements Filter
{
    public function apply(mixed $value, array $options = []): mixed
    {
        return is_string($value) ? preg_replace('/\s+/', '', $value) : $value;
    }
}
