<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use InvalidArgumentException;

class FormatDate implements Filter
{
    /**
     * Reformat a date string from one format to another using Carbon.
     *
     * This filter takes a date string ($value) in a specified current format ($options[0])
     * and converts it to a target format ($options[1]). Both formats must be provided in
     * the $options array. If the input is empty or invalid, it’s returned unchanged or
     * an exception is thrown.
     *
     * Example: $value = "2023-01-15", $options = ["Y-m-d", "d/m/Y"] => "15/01/2023"
     *
     * @param mixed              $value   The date string to reformat
     * @param array<int, string> $options Array with [0 => current format, 1 => target format]
     *
     * @throws InvalidArgumentException If $options doesn’t contain exactly two formats
     * @throws InvalidFormatException   If the date cannot be parsed
     *
     * @return mixed The reformatted date string or original value if empty
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
