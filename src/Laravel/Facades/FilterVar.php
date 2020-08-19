<?php

namespace Aporat\FilterVar\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed filterValue(string $rule_string, $value)
 *
 * @see \Aporat\FilterVar\FilterVar
 */

class FilterVar extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'filter';
    }
}
