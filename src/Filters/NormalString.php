<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class NormalString implements Filter
{
    /**
     *  Normalize string with letters, digits
     */
    public function apply(mixed $value, array $options = []): string
    {
        return (string) preg_replace('/[^A-Za-z0-9 \-:_.]/u', '', strip_tags($value));
    }
}
