<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Providers;

use Illuminate\Support\ServiceProvider;
use MyDDD\AuthDomain\Console\Commands\CleanupTokensCommand;
use MyDDD\AuthDomain\Contracts\AuthDomainInterface;
use MyDDD\AuthDomain\Repositories\Eloquent\Auth\EloquentAuthenticationRepository;
use MyDDD\AuthDomain\Repositories\Eloquent\Auth\PassportTokenRepository;
use MyDDD\AuthDomain\Repositories\Interfaces\Auth\AuthenticationRepositoryInterface;
use MyDDD\AuthDomain\Repositories\Interfaces\Auth\TokenRepositoryInterface;
use MyDDD\AuthDomain\Services\AuthDomainService;

class AuthDomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register the main service
        $this->app->singleton(AuthDomainInterface::class, AuthDomainService::class);

        // Register repositories
        $this->app->bind(AuthenticationRepositoryInterface::class, EloquentAuthenticationRepository::class);
        $this->app->bind(TokenRepositoryInterface::class, PassportTokenRepository::class);

        // Register config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/auth-domain.php',
            'auth-domain'
        );

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                CleanupTokensCommand::class,
            ]);
        }
    }

    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/auth-domain.php' => config_path('auth-domain.php'),
        ], 'auth-domain-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'auth-domain-migrations');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/auth-domain.php');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'auth-domain');
        
        // Publish translations
        $this->publishes([
            __DIR__ . '/../../resources/lang' => lang_path('vendor/auth-domain'),
        ], 'auth-domain-translations');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            AuthDomainInterface::class,
            AuthenticationRepositoryInterface::class,
            TokenRepositoryInterface::class,
        ];
    }
}
