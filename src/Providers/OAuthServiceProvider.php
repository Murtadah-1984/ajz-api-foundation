<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Providers;

use MyDDD\AuthDomain\Providers\Auth\OAuth\{
    AppleProvider,
    FacebookProvider,
    GitHubProvider,
    GoogleProvider,
    InstagramProvider
};
use MyDDD\AuthDomain\OAuth\Contracts\OAuthProviderInterface;
use Illuminate\Support\ServiceProvider;

final class OAuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerProviders();
        $this->registerFactory();
        $this->registerConfig();
    }

    private function registerProviders(): void
    {
        $this->app->bind('oauth.provider.apple', function ($app) {
            return new AppleProvider();
        });

        $this->app->bind('oauth.provider.facebook', function ($app) {
            return new FacebookProvider();
        });

        $this->app->bind('oauth.provider.github', function ($app) {
            return new GitHubProvider();
        });

        $this->app->bind('oauth.provider.google', function ($app) {
            return new GoogleProvider();
        });

        $this->app->bind('oauth.provider.instagram', function ($app) {
            return new InstagramProvider();
        });
    }

    private function registerFactory(): void
    {
        $this->app->bind(OAuthProviderInterface::class, function ($app, array $parameters) {
            $provider = $parameters['provider'] ?? null;
            
            if (!$provider) {
                throw new \InvalidArgumentException('OAuth provider not specified');
            }

            if (!config("services.oauth.providers.{$provider}.enabled", false)) {
                throw new \InvalidArgumentException("OAuth provider '{$provider}' is not enabled");
            }
            
            return match ($provider) {
                'apple' => $app->make('oauth.provider.apple'),
                'facebook' => $app->make('oauth.provider.facebook'),
                'github' => $app->make('oauth.provider.github'),
                'google' => $app->make('oauth.provider.google'),
                'instagram' => $app->make('oauth.provider.instagram'),
                default => throw new \InvalidArgumentException("Unsupported OAuth provider: {$provider}")
            };
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/oauth.php',
            'services.oauth'
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../Config/oauth.php' => config_path('services.oauth.php'),
            ], 'auth-oauth-config');
        }
    }
}
