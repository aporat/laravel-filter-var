<?php

/**
 * Configuration for the Laravel Filter Var package.
 *
 * Here you can register your own custom filter classes. When you add a filter here,
 * it becomes available to use in your application just like the built-in filters.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Custom Filters
    |--------------------------------------------------------------------------
    |
    | This array allows you to register your own custom filter classes.
    | The key should be the alias you want to use for the filter (e.g., 'JsonDecode'),
    | and the value should be the fully qualified class name of your filter implementation.
    |
    | All custom filters must implement the Aporat\FilterVar\Contracts\Filter interface.
    |
    */
    'custom_filters' => [
        // Example:
        // 'JsonDecode' => \App\Filters\JsonDecodeFilter::class,
    ],

];
