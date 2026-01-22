<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;

/**
 * Normalizes a string, keeping only Unicode letters, numbers, and basic punctuation.
 *
 * @implements Filter<mixed, string>
 */
final readonly class NormalString implements Filter
{
    /**
     * Strips HTML tags and removes non-standard characters from a string.
     *
     * This filter first removes all HTML/PHP tags, then removes any character that is not
     * a Unicode letter (`\p{L}`), a Unicode number (`\p{N}`), a space, hyphen, colon,
     * underscore, or period.
     *
     * @param  mixed  $value  The value to normalize.
     * @param  array<int, mixed>  $options  (Unused)
     * @return string The normalized string.
     */
    public function apply(mixed $value, array $options = []): string
    {
        // First, ensure the value is a string and strip any HTML/PHP tags.
        $value = strip_tags((string) $value);

        // Next, remove all characters that are not letters, numbers, or basic symbols.
        $result = preg_replace(
            pattern: '/[^\p{L}\p{N} \-:_.]/u',
            replacement: '',
            subject: $value
        );

        return $result ?? '';
    }
}
