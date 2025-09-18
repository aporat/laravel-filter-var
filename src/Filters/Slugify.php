<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

/**
 * Converts a string into a URL-friendly "slug".
 *
 * @implements Filter<mixed, string>
 */
final readonly class Slugify implements Filter
{
    /**
     * Creates a URL-friendly slug from a string.
     *
     * @param  mixed  $value  The string to convert.
     * @param  array<int, mixed>  $options  (Unused)
     * @return string The generated slug.
     */
    public function apply(mixed $value, array $options = []): string
    {
        // 1. Transliterate and ensure we have a string for the next step.
        $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', (string) $value);
        $value = ($value === false) ? '' : $value;

        // 2. Replace non-alphanumerics and handle potential null from preg_replace.
        $value = preg_replace('/[^a-zA-Z0-9]+/', '-', $value);
        $value = $value ?? '';

        // 3. Trim and convert to lowercase.
        $value = trim($value, '-');

        return strtolower($value);
    }
}
