<?php

namespace Ajz\ApiBase\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Ajz\ApiBase\ApiBaseServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Additional setup if needed
    }

    protected function getPackageProviders($app): array
    {
        return [
            ApiBaseServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'ApiCache' => 'Ajz\ApiBase\Facades\ApiCache',
            'ApiMonitor' => 'Ajz\ApiBase\Facades\ApiMonitor',
            'ApiSecurity' => 'Ajz\ApiBase\Facades\ApiSecurity',
        ];
    }

    protected function defineEnvironment($app): void
    {
        // Set up environment variables for testing
        $app['config']->set('api-base.signature_ttl', 300);
        $app['config']->set('api-base.key_expiry', 31536000);
        $app['config']->set('api-base.default_tier', 'bronze');
    }
}
