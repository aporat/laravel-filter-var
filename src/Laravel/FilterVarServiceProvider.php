<?php

namespace Aporat\FilterVar\Laravel;

use Aporat\FilterVar\FilterVar;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class FilterVarServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerResources();
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
     * Register resources.
     *
     * @return void
     */
    public function registerResources()
    {
        if ($this->isLumen()) {
            $this->app->configure('filter_var');
        } elseif ($this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__.'/../Config/filter_var.php' => config_path('filter_var.php')],
                'filter_var'
            );
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/filter_var.php',
            'filter_var'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string
     */
    public function provides()
    {
        return 'filter';
    }

    /**
     * Check if package is running under Lumen app
     *
     * @return bool
     */
    protected function isLumen()
    {
        return Str::contains($this->app->version(), 'Lumen') === true;
    }
}
