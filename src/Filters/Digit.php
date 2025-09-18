<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

/**
 * Extracts only digit characters from a value.
 *
 * @implements Filter<mixed, string>
 */
final readonly class Digit implements Filter
{
    /**
     * Removes all non-digit characters from the input.
     *
     * @param  mixed  $value  The value to filter.
     * @param  array<int, mixed>  $options  (Unused)
     * @return string A string containing only digits.
     */
    public function apply(mixed $value, array $options = []): string
    {
        $result = preg_replace(
            pattern: '/\D/',
            replacement: '',
            subject: (string) $value
        );

        return $result ?? '';
    }
}
