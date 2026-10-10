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
        if (empty($value) || ! is_scalar($value)) {
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

            if ($date === null || self::hasParseWarnings()) {
                return $value;
            }

            return $date->format(trim($targetFormat));
        } catch (InvalidFormatException) {
            return $value;
        }
    }

    /**
     * Whether the last parse produced warnings, e.g. an impossible date such as
     * 2023-02-30 that would otherwise silently roll over to the next month.
     */
    private static function hasParseWarnings(): bool
    {
        $errors = Carbon::getLastErrors();

        return is_array($errors) && $errors['warning_count'] > 0;
    }
}
