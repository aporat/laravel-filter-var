<?php

namespace Aporat\FilterVar\Filters;

use Aporat\FilterVar\Contracts\Filter;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class Cast implements Filter
{
    /**
     * Cast the given value to a specified type.
     *
     * The target type is determined by the first element in $options (e.g., $options[0]).
     * Supported types: int, integer, float, real, double, string, bool, boolean, object,
     * array, collection. If an unsupported type is provided, an exception is thrown.
     *
     * @param mixed $value   The value to cast
     * @param array $options Options array where the first element specifies the target type
     *
     * @throws InvalidArgumentException If the type is invalid or not provided
     *
     * @return mixed The value cast to the specified type
     */
    public function apply(mixed $value, array $options = []): mixed
    {
        $type = $options[0] ?? null;
        switch ($type) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'object':
                return is_array($value) ? (object) $value : json_decode($value, false);
            case 'array':
                return json_decode($value, true);
            case 'collection':
                $array = is_array($value) ? $value : json_decode($value, true);

                return new Collection($array);
            default:
                throw new InvalidArgumentException("Wrong FilterVar casting format: $type.");
        }
    }
}
