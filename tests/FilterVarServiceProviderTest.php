<?php

namespace Aporat\FilterVar\Tests;

use Aporat\FilterVar\FilterVar;
use Aporat\FilterVar\Laravel\FilterVarServiceProvider;
use Orchestra\Testbench\TestCase;

class FilterVarServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [FilterVarServiceProvider::class];
    }

    public function test_service_is_registered_as_singleton(): void
    {
        $this->assertTrue($this->app->bound('filter-var'));

        $instance1 = $this->app->make('filter-var');
        $instance2 = $this->app->make('filter-var');

        $this->assertInstanceOf(FilterVar::class, $instance1);
        $this->assertSame($instance1, $instance2, 'FilterVar should be a singleton');
    }

    public function test_config_is_merged(): void
    {
        $config = $this->app['config']->get('filter-var');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('custom_filters', $config);
        $this->assertEmpty($config['custom_filters'], 'Default custom_filters should be an empty array');
    }

    public function test_config_is_publishable(): void
    {
        $sourcePath = realpath(__DIR__.'/../config/filter-var.php');
        $targetPath = $this->app->configPath('filter-var.php');

        $this->artisan('vendor:publish', ['--provider' => FilterVarServiceProvider::class, '--force' => true]);

        $this->assertFileExists($targetPath);
        $this->assertFileEquals($sourcePath, $targetPath);

        // Clean up
        unlink($targetPath);
    }
}
