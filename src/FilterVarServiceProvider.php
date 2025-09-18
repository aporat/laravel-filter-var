<?php

namespace Aporat\FilterVar;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class FilterVarServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The absolute path to the package's configuration file.
     */
    private readonly string $configPath;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->configPath = __DIR__.'/../config/filter-var.php';
    }

    /**
     * Register the service provider bindings in the container.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath, 'filter-var');

        // Bind the main class to the container. This is better than binding a string key
        // as it allows for type-hinting and is refactor-friendly.
        $this->app->singleton(
            abstract: FilterVar::class,
            concrete: fn (Application $app): FilterVar => new FilterVar($app->make('config')->get('filter-var', []))
        );

        // Alias the class to the string used by the facade for compatibility.
        $this->app->alias(FilterVar::class, 'filter-var');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Assets should only be published when running in the console
        // to avoid unnecessary work during a web request.
        if ($this->app->runningInConsole()) {
            $this->publishes(
                paths: [$this->configPath => config_path('filter-var.php')],
                groups: 'config'
            );
        }
    }

    /**
     * Get the services provided by this provider.
     *
     * @return array<int, class-string|string>
     */
    public function provides(): array
    {
        return [
            FilterVar::class,
            'filter-var',
        ];
    }
}
