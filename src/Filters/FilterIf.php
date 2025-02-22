<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

class FilterIf implements Filter
{
    /**
     * Check if a condition is met based on the input array and options.
     *
     * This filter verifies if the input value is an array and contains a key (specified by
     * $options[0]) whose value matches the expected value (specified by $options[1]).
     * Returns true if the condition is met, false otherwise. Non-array inputs always return false.
     *
     * Example: If $value = ['status' => 'active'], $options = ['status', 'active'], returns true.
     *
     * @param mixed $value The input value (expected to be an array)
     * @param array<int, mixed> $options Array where $options[0] is the key and $options[1] is the expected value
     * @return mixed Returns bool (true if condition matches, false otherwise)
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        return array_key_exists($options[0], $value) && $value[$options[0]] === $options[1];
    }
}
