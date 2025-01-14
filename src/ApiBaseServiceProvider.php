<?php

declare(strict_types=1);

namespace Ajz\ApiBase;

use RuntimeException;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\Application;
use Ajz\ApiBase\Contracts\SecurityServiceInterface;
use Ajz\ApiBase\Services\Base\{
    EnhancedCacheService,
    RateLimitService,
    PerformanceMonitoringService,
    SecurityService
};
use Ajz\ApiBase\Commands\{
    InstallPackage,
    PublishConfig,
    CleanupExpiredKeys
};

final class ApiBaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->validateDatabaseSchema();

        // Publish configurations
        $this->publishes([
            __DIR__ . '/../../config' => config_path('api-base'),
        ], 'api-base-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'api-base-migrations');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallPackage::class,
                PublishConfig::class,
                CleanupExpiredKeys::class,
            ]);

            // Schedule cleanup command
            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('api:cleanup-keys')->daily();
            });
        }

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        // Register middlewares
        $this->app['router']->aliasMiddleware('api.rate-limit', \Ajz\ApiBase\Http\Middleware\EnhancedApiRateLimit::class);
    }

    /**
     * Validate required database schema
     */
    protected function validateDatabaseSchema()
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        if (!Schema::hasTable('api_keys')) {
            throw new RuntimeException(
                'The api_keys table is missing. Please run migrations: php artisan migrate'
            );
        }

        if (!Schema::hasTable('rate_limit_tiers')) {
            throw new RuntimeException(
                'The rate_limit_tiers table is missing. Please run migrations: php artisan migrate'
            );
        }
    }

    /**
     * Register services and bindings
     */
    public function register(): void
    {
        $this->registerConfigs();
        $this->registerServices();
    }

    /**
     * Register configuration files
     */
    protected function registerConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/api-base/cache.php', 'api-base.cache');
        $this->mergeConfigFrom(__DIR__ . '/../../config/api-base/monitor.php', 'api-base.monitor');
        $this->mergeConfigFrom(__DIR__ . '/../../config/api-base/rate-limit.php', 'api-base.rate-limit');

    }

    /**
     * Register service bindings
     */
    protected function registerServices(): void
    {
        // Cache Service
        $this->app->singleton('api-base.cache', function (Application $app) {
            return new EnhancedCacheService(
                config('api-base.cache.prefix'),
                config('api-base.cache.default_ttl')
            );
        });

        // Monitor Service
        $this->app->singleton('api-base.monitor', function (Application $app) {
            return new PerformanceMonitoringService();
        });

        // Rate Limit Service
        $this->app->singleton('api-base.rate-limit', function (Application $app) {
            return new RateLimitService();
        });

        // Security Service
        $this->app->singleton(SecurityServiceInterface::class, SecurityService::class);
        $this->app->singleton('api-base.security', function (Application $app) {
            return $app->make(SecurityServiceInterface::class);
        });
    }


}
