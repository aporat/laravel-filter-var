<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;
use InvalidArgumentException;

/**
 * Conditionally checks if a key in an array matches an expected value.
 *
 * @implements Filter<mixed, bool>
 */
final readonly class FilterIf implements Filter
{
    /**
     * Checks if the condition is met.
     *
     * Returns true only if the input is an array and the specified key's
     * value matches the expected value. Options parsed from a rule string are
     * always strings, so scalar values are compared by their string form
     * (e.g. int 1 matches '1'); other values are compared strictly.
     *
     * @param  mixed  $value  The input value, expected to be an array.
     * @param  array<int, mixed>  $options  [$key, $expectedValue]
     * @return bool True if the condition is met, false otherwise.
     *
     * @throws InvalidArgumentException If options are not configured correctly.
     */
    public function apply(mixed $value, array $options = []): bool
    {
        if (count($options) < 2) {
            throw new InvalidArgumentException('The "FilterIf" filter requires two options: a key and a value to match.');
        }

        [$key, $expectedValue] = $options;

        if (! is_array($value)) {
            return false;
        }

        if (! array_key_exists(key: $key, array: $value)) {
            return false;
        }

        $actualValue = $value[$key];

        if (is_scalar($actualValue) && is_scalar($expectedValue)) {
            return (string) $actualValue === (string) $expectedValue;
        }

        return $actualValue === $expectedValue;
    }
}
