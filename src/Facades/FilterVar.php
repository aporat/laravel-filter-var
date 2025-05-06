<?php

namespace Aporat\FilterVar\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for the FilterVar service.
 *
 * Provides a static interface to the FilterVar class, allowing easy access to filtering
 * functionality within Laravel applications.
 *
 * @method static mixed filterValue(string $ruleString, mixed $value) Apply a chain of filters to a value based on a rule string
 *
 * @see \Aporat\FilterVar\FilterVar
 */
class FilterVar extends Facade
{
    /**
     * Get the registered name of the component in the service container.
     *
     * @return string The binding key for the FilterVar service
     */
    protected static function getFacadeAccessor(): string
    {
        return 'filter-var';
    }
}
