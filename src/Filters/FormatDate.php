<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use InvalidArgumentException;

/**
 * Reformats a date string from one format to another.
 *
 * @implements Filter<mixed, string|mixed>
 */
final readonly class FormatDate implements Filter
{
    /**
     * Reformats a date string using Carbon.
     *
     * @param  mixed  $value  The date string to reformat.
     * @param  array<int, string>  $options  [$currentFormat, $targetFormat]
     * @return mixed The new date string or the original value if invalid.
     *
     * @throws InvalidArgumentException If options are not configured correctly.
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        if (empty($value)) {
            return $value;
        }

        if (count($options) !== 2) {
            throw new InvalidArgumentException('The "FormatDate" filter requires two options: the current format and the target format.');
        }

        [$currentFormat, $targetFormat] = $options;

        try {
            $date = Carbon::createFromFormat(
                format: trim($currentFormat),
                time: (string) $value
            );

            if ($date === null) {
                return $value;
            }

            return $date->format($targetFormat);
        } catch (InvalidFormatException) {
            return $value;
        }
    }
}
