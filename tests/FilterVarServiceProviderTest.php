<?php

namespace Aporat\FilterVar\Tests;

use Aporat\FilterVar\FilterVar;
use Aporat\FilterVar\FilterVarServiceProvider;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class FilterVarServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [FilterVarServiceProvider::class];
    }

    #[Test]
    public function service_is_registered_as_singleton(): void
    {
        // Test that both the class and the alias are bound in the container.
        self::assertTrue($this->app->bound(FilterVar::class));
        self::assertTrue($this->app->bound('filter-var'));

        // Resolve the service using both the class name and the alias.
        $instance1 = $this->app->make(FilterVar::class);
        $instance2 = $this->app->make('filter-var');

        // Assert that both resolutions return the exact same instance.
        self::assertInstanceOf(FilterVar::class, $instance1);
        self::assertSame($instance1, $instance2, 'FilterVar should be a singleton, and the alias should point to the same instance.');
    }

    #[Test]
    public function config_is_merged(): void
    {
        $config = $this->app['config']->get('filter-var');

        self::assertIsArray($config);
        self::assertArrayHasKey('custom_filters', $config);
        self::assertEmpty($config['custom_filters'], 'Default custom_filters should be an empty array');
    }

    #[Test]
    public function config_is_publishable(): void
    {
        $sourcePath = realpath(__DIR__.'/../config/filter-var.php');
        $targetPath = $this->app->configPath('filter-var.php');

        // Use named arguments for better clarity.
        $this->artisan(
            command: 'vendor:publish',
            parameters: [
                '--provider' => FilterVarServiceProvider::class,
                '--force' => true,
            ]
        );

        self::assertFileExists($targetPath);
        self::assertFileEquals($sourcePath, $targetPath);

        // Clean up the published file.
        unlink($targetPath);
    }
}
