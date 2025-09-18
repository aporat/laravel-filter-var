<?php

namespace Aporat\FilterVar\Contracts;

/**
 * Defines the contract for a filter that transforms a value.
 *
 * @template TValue The type of the value being passed into the filter.
 * @template TFiltered The type of the value after the filter is applied.
 */
interface Filter
{
    /**
     * Apply the filter to the given value.
     *
     * @param  TValue  $value  The value to filter
     * @param  array<int, mixed>  $options  Optional configuration for the filter
     * @return mixed The filtered value
     */
    public function apply(mixed $value, array $options = []): mixed;
}
