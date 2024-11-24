<?php

namespace Aporat\FilterVar\Laravel;

use Aporat\FilterVar\FilterVar;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Support\ServiceProvider;

class FilterVarServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    private function setupConfig(): void
    {
        $source = realpath($raw = __DIR__.'/../config/filter_var.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('filter_var.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('filter_var');
        }

        $this->mergeConfigFrom($source, 'filter_var');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerFilterService();
    }

    /**
     * Register currency provider.
     *
     * @return void
     */
    public function registerFilterService()
    {
        $this->app->singleton('filter', function ($app) {
            $config = $app->make('config')->get('filter_var');
            return new FilterVar($config);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides(): array
    {
        return [
            'filter',
        ];
    }
}
