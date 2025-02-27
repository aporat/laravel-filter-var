<?php

namespace Aporat\FilterVar\Laravel;

use Aporat\FilterVar\FilterVar;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class FilterVarServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Path to the package's config file.
     */
    protected string $configPath = __DIR__.'/../../config/filter-var.php';

    /**
     * Register the service provider bindings in the container.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath, 'filter-var');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([$this->configPath => config_path('filter-var.php')], 'config');
        $this->registerFilterService();
    }

    /**
     * Register the FilterVar singleton in the application container.
     */
    protected function registerFilterService(): void
    {
        $this->app->singleton('filter-var', fn ($app) => new FilterVar($app['config']['filter-var']));
    }

    /**
     * Get the services provided by this provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return ['filter-var'];
    }
}
