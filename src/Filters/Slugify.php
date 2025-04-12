<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class Slugify implements Filter
{
    public function apply(mixed $value, array $options = []): mixed
    {
        $value = preg_replace('/[^A-Za-z0-9]+/', '-', (string) $value); // replace all non-alphanum with dash
        $value = trim($value, '-'); // remove leading/trailing dashes

        return strtolower($value);
    }
}
