<?php

namespace MyDDD\AuthDomain\Providers\Auth\OAuth;

use MyDDD\AuthDomain\Repositories\TokenRepositoryInterface;
use MyDDD\AuthDomain\Repositories\PassportTokenRepository;
use MyDDD\AuthDomain\OAuth\Providers\GithubProvider;
use MyDDD\AuthDomain\OAuth\Providers\GoogleProvider;
use MyDDD\AuthDomain\OAuth\Providers\FacebookProvider;
use MyDDD\AuthDomain\OAuth\Providers\AppleProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind TokenRepository interface to Passport implementation
        $this->app->bind(TokenRepositoryInterface::class, PassportTokenRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register OAuth routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        // Register custom OAuth providers
        /** @var SocialiteFactory $socialite */
        $socialite = $this->app->make(SocialiteFactory::class);

        $socialite->extend('github', function () {
            return $this->app->make(GithubProvider::class);
        });

        $socialite->extend('google', function () {
            return $this->app->make(GoogleProvider::class);
        });

        $socialite->extend('facebook', function () {
            return $this->app->make(FacebookProvider::class);
        });

        $socialite->extend('apple', function () {
            return $this->app->make(AppleProvider::class);
        });

        // Configure Passport
        \Laravel\Passport\Passport::tokensExpireIn(now()->addDays(15));
        \Laravel\Passport\Passport::refreshTokensExpireIn(now()->addDays(30));
        \Laravel\Passport\Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        // Enable password grant (if needed)
        // \Laravel\Passport\Passport::enablePasswordGrant();

        // Enable implicit grant (if needed)
        // \Laravel\Passport\Passport::enableImplicitGrant();
    }
}
