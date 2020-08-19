<?php

namespace Aporat\FilterVar\Laravel;

use Aporat\FilterVar\FilterVar;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class FilterVarServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Bootstrap the application service
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__.'/../Config/filter_var.php' => config_path('filter_var.php')],
                'filter_var'
            );
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('filter_var');
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/filter_var.php',
            'filter_var'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('filter', function ($app) {
            $config = $app->make('config')->get('filter_var');
            return new FilterVar($config);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['filter', FilterVar::class];
    }
}
