<?php

namespace Aporat\FilterVar;

use Aporat\FilterVar\Contracts\Filter;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
     * The key is the normalized filter name (see normalizeName()).
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
     * The name is normalized the same way rule names are resolved, so
     * 'media_real_id', 'MediaRealId' and 'mediaRealId' all refer to the same filter.
     *
     * @param  string  $name  The name of the filter (e.g., 'trim').
     * @param  string  $class  The filter's class path; must implement the Filter contract.
     *
     * @throws InvalidArgumentException If the class does not implement the Filter contract.
     */
    public function extend(string $name, string $class): void
    {
        if (! is_a($class, Filter::class, true)) {
            throw new InvalidArgumentException(
                "Filter class '$class' registered for '$name' must implement ".Filter::class.'.'
            );
        }

        $normalizedName = self::normalizeName($name);

        $this->filters[$normalizedName] = $class;

        // Drop any instance resolved for a previously registered class.
        unset($this->resolvedFilters[$normalizedName]);
    }

    /**
     * Apply a chain of filters to a value based on a rule string.
     */
    public function filterValue(string $ruleString, mixed $value): mixed
    {
        foreach (explode('|', $ruleString) as $segment) {
            // Skip empty segments, e.g. an empty rule string or a trailing '|'.
            if (trim($segment) === '') {
                continue;
            }

            $rule = ValidationRuleParser::parse($segment);
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
        $normalizedName = self::normalizeName($name);

        if (! isset($this->filters[$normalizedName])) {
            throw new InvalidArgumentException("No filter registered for the name '$name'.");
        }

        return $this->resolvedFilters[$normalizedName] ??= new $this->filters[$normalizedName];
    }

    /**
     * Normalize a filter name so registration and lookup use the same key.
     */
    private static function normalizeName(string $name): string
    {
        return strtolower(Str::studly(trim($name)));
    }
}
