<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;
use Carbon\Carbon;
use InvalidArgumentException;

class FormatDate implements Filter
{
    /**
     * @param mixed $value
     * @param array $options
     *
     * @return mixed
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        if (!$value) {
            return $value;
        }
        if (sizeof($options) != 2) {
            throw new InvalidArgumentException('The FilterVar Format Date filter requires both the current date format as well as the target format.');
        }
        $currentFormat = trim($options[0]);
        $targetFormat = trim($options[1]);

        return Carbon::createFromFormat($currentFormat, $value)->format($targetFormat);
    }
}
