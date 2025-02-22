<?php

namespace Aporat\FilterVar\Contracts;

interface Filter
{
    /**
     * Apply the filter to the given value.
     *
     * @param mixed                $value   The value to filter
     * @param array<string, mixed> $options Optional configuration for the filter
     *
     * @return mixed The filtered value
     */
    public function apply(mixed $value, array $options = []): mixed;
}
