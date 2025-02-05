<?php

namespace Aporat\FilterVar;

use Aporat\FilterVar\Contracts\Filter;
use Aporat\FilterVar\Filters\Uppercase;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationRuleParser;
use InvalidArgumentException;

class FilterVar
{
    /**
     *  Filters to apply.
     *
     * @var array
     */
    protected array $rules;

    /**
     *  Available filters as $name => $classPath.
     *
     * @var array
     */
    protected mixed $filters = [];

    /**
     *  Create a new filter instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $filters = [
            'Capitalize'  => Filters\Capitalize::class,
            'Cast'        => Filters\Cast::class,
            'Escape'      => Filters\EscapeHTML::class,
            'FormatDate'  => Filters\FormatDate::class,
            'Lowercase'   => Filters\Lowercase::class,
            'Uppercase'   => Filters\Uppercase::class,
            'Trim'        => Filters\Trim::class,
            'StripTags'   => Filters\StripTags::class,
            'Digit'       => Filters\Digit::class,
            'FilterIf'    => Filters\FilterIf::class
        ];

        if (Arr::has($config, 'custom_filters')) {
            $filters = array_merge($filters, Arr::get($config, 'custom_filters'));
        }

        $this->filters = $filters;
    }

    /**
     *  Apply the given filter by its name.
     *
     * @param array $rule
     * @param mixed $value
     *
     * @return mixed
     */
    protected function applyFilter(array $rule, mixed $value): mixed
    {
        $name = $rule[0];
        $options = $rule[1];

        // If the filter does not exist, throw an Exception:
        if (!isset($this->filters[$name])) {
            throw new InvalidArgumentException("No filter found by the name of $name");
        }

        $filter_name = $this->filters[$name];

        return new $filter_name()->apply($value, $options);

    }

    /**
     * @param string $rule_string
     * @param mixed  $value
     *
     * @return mixed
     */
    public function filterValue(string $rule_string, mixed $value): mixed
    {
        $rules = [];
        $rules_strings = explode('|', $rule_string);
        foreach ($rules_strings as $rule_string) {
            $rules[] = ValidationRuleParser::parse($rule_string);
        }

        foreach ($rules as $rule) {
            $value = $this->applyFilter($rule, $value);
        }

        return $value;
    }
}
