<?php

declare(strict_types=1);

namespace App\Domains\Shared\Providers;

use App\Domains\Shared\Environment\Contracts\DomainEnvironmentManagerInterface;
use App\Domains\Shared\Environment\DomainEnvironmentManager;
use App\Domains\Shared\Translation\Contracts\DomainTranslationManagerInterface;
use App\Domains\Shared\Translation\DomainTranslationManager;
use Illuminate\Support\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register domain services.
     */
    public function register(): void
    {
        $this->registerEnvironmentManager();
        $this->registerTranslationManager();
    }

    /**
     * Register the environment manager service.
     */
    private function registerEnvironmentManager(): void
    {
        $this->app->singleton(DomainEnvironmentManagerInterface::class, function () {
            return new DomainEnvironmentManager();
        });

        $this->app->alias(
            DomainEnvironmentManagerInterface::class,
            'domain.environment'
        );
    }

    /**
     * Register the translation manager service.
     */
    private function registerTranslationManager(): void
    {
        $this->app->singleton(DomainTranslationManagerInterface::class, function () {
            return new DomainTranslationManager();
        });

        $this->app->alias(
            DomainTranslationManagerInterface::class,
            'domain.translation'
        );
    }

    /**
     * Bootstrap domain services.
     */
    public function boot(): void
    {
        // Future domain-wide bootstrapping can go here
    }
}
