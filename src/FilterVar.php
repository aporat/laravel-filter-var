<?php

namespace Aporat\FilterVar;

use Aporat\FilterVar\Contracts\Filter;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationRuleParser;
use InvalidArgumentException;

final class FilterVar
{
    /**
     * The default filter mappings.
     *
     * @var array<string, class-string<Filter<mixed, mixed>>>
     */
    private const array DEFAULT_FILTERS = [
        'Capitalize' => Filters\Capitalize::class,
        'Cast' => Filters\Cast::class,
        'Escape' => Filters\EscapeHTML::class,
        'FormatDate' => Filters\FormatDate::class,
        'Lowercase' => Filters\Lowercase::class,
        'NormalString' => Filters\NormalString::class,
        'Uppercase' => Filters\Uppercase::class,
        'Trim' => Filters\Trim::class,
        'StripTags' => Filters\StripTags::class,
        'Digit' => Filters\Digit::class,
        'FilterIf' => Filters\FilterIf::class,
        'RemoveWhitespace' => Filters\RemoveWhitespace::class,
        'Slugify' => Filters\Slugify::class,
    ];

    /**
     * The registered filter mappings.
     * The key is the normalized (lowercase) filter name.
     *
     * @var array<string, class-string<Filter<mixed, mixed>>>
     */
    private array $filters = [];

    /**
     * Cache of resolved filter instances.
     *
     * @var array<string, Filter<mixed, mixed>>
     */
    private array $resolvedFilters = [];

    /**
     * @param  array{custom_filters?: array<string, class-string<Filter<mixed, mixed>>>}  $config
     */
    public function __construct(array $config = [])
    {
        $customFilters = Arr::get($config, 'custom_filters', []);

        foreach (array_merge(self::DEFAULT_FILTERS, $customFilters) as $name => $class) {
            $this->extend($name, $class);
        }
    }

    /**
     * Register a new custom filter at runtime.
     *
     * @param  string  $name  The name of the filter (e.g., 'trim').
     * @param  class-string<Filter<mixed, mixed>>  $class  The filter's class path.
     */
    public function extend(string $name, string $class): void
    {
        $this->filters[strtolower($name)] = $class;
    }

    /**
     * Apply a chain of filters to a value based on a rule string.
     */
    public function filterValue(string $ruleString, mixed $value): mixed
    {
        $rules = array_map(
            static fn (string $rule): array => ValidationRuleParser::parse($rule),
            explode('|', $ruleString)
        );

        foreach ($rules as $rule) {
            [$name, $options] = [$rule[0], $rule[1] ?? []];

            $filter = $this->resolveFilter($name);
            $value = $filter->apply($value, $options);
        }

        return $value;
    }

    /**
     * Resolve a filter instance, using a cache to avoid re-instantiation.
     *
     * @return Filter<mixed, mixed>
     *
     * @throws InvalidArgumentException
     */
    private function resolveFilter(string $name): Filter
    {
        $normalizedName = strtolower($name);

        if (! isset($this->filters[$normalizedName])) {
            throw new InvalidArgumentException("No filter registered for the name '$name'.");
        }

        return $this->resolvedFilters[$normalizedName] ??= new $this->filters[$normalizedName];
    }
}
