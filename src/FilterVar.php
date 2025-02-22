<?php

namespace Aporat\FilterVar;

use Aporat\FilterVar\Contracts\Filter;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationRuleParser;
use InvalidArgumentException;

class FilterVar
{
    /**
     * Available filters mapped as filter name => class path.
     *
     * @var array<string, class-string<Filter>>
     */
    protected array $filters = [];

    /**
     * Create a new FilterVar instance.
     *
     * @param array<string, mixed> $config Configuration array, optionally containing 'custom_filters'
     */
    public function __construct(array $config = [])
    {
        $this->filters = array_merge($this->getDefaultFilters(), Arr::get($config, 'custom_filters', []));
    }

    /**
     * Get the default filter mappings.
     *
     * @return array<string, class-string<Filter>>
     */
    protected function getDefaultFilters(): array
    {
        return [
            'Capitalize' => Filters\Capitalize::class,
            'Cast'       => Filters\Cast::class,
            'Escape'     => Filters\EscapeHTML::class,
            'FormatDate' => Filters\FormatDate::class,
            'Lowercase'  => Filters\Lowercase::class,
            'Uppercase'  => Filters\Uppercase::class,
            'Trim'       => Filters\Trim::class,
            'StripTags'  => Filters\StripTags::class,
            'Digit'      => Filters\Digit::class,
            'FilterIf'   => Filters\FilterIf::class,
        ];
    }

    /**
     * Apply a single filter to a value.
     *
     * @param array{0: string, 1?: array<string, mixed>} $rule  Filter name and optional options
     * @param mixed                                      $value The value to filter
     *
     * @throws InvalidArgumentException If the filter name is not registered
     *
     * @return mixed The filtered value
     */
    protected function applyFilter(array $rule, mixed $value): mixed
    {
        $name = $rule[0];
        $options = $rule[1] ?? [];

        if (!isset($this->filters[$name])) {
            throw new InvalidArgumentException("No filter registered for the name '$name'.");
        }

        /** @var Filter $filter */
        $filter = new $this->filters[$name]();

        return $filter->apply($value, $options);
    }

    /**
     * Apply a chain of filters to a value based on a rule string.
     *
     * @param string $ruleString Filter rules (e.g., "trim|uppercase")
     * @param mixed  $value      The value to filter
     *
     * @return mixed The filtered value
     */
    public function filterValue(string $ruleString, mixed $value): mixed
    {
        $rules = array_map(
            fn (string $rule) => ValidationRuleParser::parse($rule),
            explode('|', $ruleString)
        );

        foreach ($rules as $rule) {
            $value = $this->applyFilter($rule, $value);
        }

        return $value;
    }
}
