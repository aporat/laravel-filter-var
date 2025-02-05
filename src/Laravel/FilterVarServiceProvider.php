<?php

namespace Aporat\FilterVar\Laravel;

use Aporat\FilterVar\FilterVar;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class FilterVarServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $configPath = __DIR__.'/../../config/filter-var.php';
        $this->mergeConfigFrom($configPath, 'filter-var');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $configPath = __DIR__.'/../../config/filter-var.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        $this->registerFilterService();
    }

    /**
     * Get the config path.
     *
     * @return string
     */
    protected function getConfigPath(): string
    {
        return config_path('filter-var.php');
    }

    /**
     * Register the filter provider.
     *
     * @return void
     */
    public function registerFilterService(): void
    {
        $this->app->singleton('filter-var', function ($app) {
            $config = $app->make('config')->get('filter-var');

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
            'filter-var',
        ];
    }
}
