<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class Slugify implements Filter
{
    public function apply(mixed $value, array $options = []): string
    {
        $value = preg_replace('/[^A-Za-z0-9]+/', '-', (string) $value); // replace all non-alphanumeric with dash
        $value = trim($value, '-'); // remove leading/trailing dashes

        return strtolower($value);
    }
}
