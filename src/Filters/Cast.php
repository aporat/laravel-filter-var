<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use stdClass;

/**
 * Casts a value to a specified type.
 *
 * @implements Filter<mixed, mixed>
 */
final readonly class Cast implements Filter
{
    /**
     * Casts the given value to a specified type.
     *
     * @param  mixed  $value  The value to cast.
     * @param  array<int, string>  $options  Options array where the first element is the target type.
     * @return mixed The value cast to the specified type.
     *
     * @throws InvalidArgumentException If a casting type is not provided or is unsupported.
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        $type = $options[0] ?? null;

        if ($type === null) {
            throw new InvalidArgumentException('Casting type must be provided for the "Cast" filter.');
        }

        return match ($type) {
            'int', 'integer' => (int) $value,
            'real', 'float', 'double' => (float) $value,
            'string' => (string) $value,
            'bool', 'boolean' => $this->toBool($value),
            'array' => $this->toArray($value),
            'object' => $this->toObject($value),
            'collection' => new Collection($this->toArray($value)),
            default => throw new InvalidArgumentException("Invalid casting type provided: '$type'."),
        };
    }

    /**
     * Convert a mixed value to a boolean.
     *
     * Strings such as 'false', '0', 'no', 'off' and '' become false, and 'true', '1',
     * 'yes', 'on' become true (case-insensitive). Unrecognized strings and non-string
     * values fall back to a native (bool) cast.
     */
    private function toBool(mixed $value): bool
    {
        if (is_string($value)) {
            return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? (bool) $value;
        }

        return (bool) $value;
    }

    /**
     * Convert a mixed value to an array.
     *
     * @return array<mixed>
     */
    private function toArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode(json: $value, associative: true);

            // JSON scalars (e.g. '123', 'true') and invalid JSON yield an empty array.
            return is_array($decoded) ? $decoded : [];
        }

        // Handles objects and other types
        return (array) $value;
    }

    /**
     * Convert a mixed value to an object.
     */
    private function toObject(mixed $value): object
    {
        if (is_object($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode(json: $value, associative: false);

            if (is_object($decoded)) {
                return $decoded;
            }

            // JSON lists (e.g. '[1,2]') are converted like arrays; scalars and invalid JSON yield an empty object.
            return is_array($decoded) ? (object) $decoded : new stdClass;
        }

        // Handles arrays and other types
        return (object) $value;
    }
}
