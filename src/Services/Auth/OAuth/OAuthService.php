<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Services\Auth\OAuth;

use MyDDD\AuthDomain\Repositories\Interfaces\Auth\OAuthProviderInterface;
use MyDDD\AuthDomain\DataTransferObjects\Auth\OAuth\OAuthUserDTO;
use MyDDD\AuthDomain\Exceptions\Auth\OAuth\OAuthException;
use MyDDD\AuthDomain\Providers\Auth\OAuth\GitHubProvider;
use MyDDD\AuthDomain\DataTransferObjects\TokenDTO;
use Illuminate\Support\Facades\Cache;

final class OAuthService
{
    /**
     * @var array<string, class-string<OAuthProviderInterface>>
     */
    private array $providers = [
        'github' => GitHubProvider::class,
        // Add other providers here
    ];

    /**
     * Get the authorization URL for a provider
     */
    public function getAuthorizationUrl(string $provider): string
    {
        return $this->resolveProvider($provider)->getAuthorizationUrl();
    }

    /**
     * Get user details from provider
     */
    public function getUserByCode(string $provider, string $code): OAuthUserDTO
    {
        $cacheKey = "oauth_user_{$provider}_{$code}";

        return Cache::remember($cacheKey, 300, function () use ($provider, $code) {
            return $this->resolveProvider($provider)->getUserByCode($code);
        });
    }

    /**
     * Get access token from provider
     */
    public function getAccessToken(string $provider, string $code): TokenDTO
    {
        $cacheKey = "oauth_token_{$provider}_{$code}";

        return Cache::remember($cacheKey, 300, function () use ($provider, $code) {
            return $this->resolveProvider($provider)->getAccessToken($code);
        });
    }

    /**
     * Check if a provider is supported
     */
    public function isProviderSupported(string $provider): bool
    {
        return isset($this->providers[$provider]);
    }

    /**
     * Get all supported providers
     *
     * @return array<string>
     */
    public function getSupportedProviders(): array
    {
        return array_keys($this->providers);
    }

    /**
     * Resolve a provider instance
     */
    private function resolveProvider(string $provider): OAuthProviderInterface
    {
        if (!$this->isProviderSupported($provider)) {
            throw OAuthException::providerNotSupported($provider);
        }

        $providerClass = $this->providers[$provider];

        try {
            return new $providerClass();
        } catch (\Error $e) {
            // Catches class not found or other instantiation errors
            throw OAuthException::invalidProvider();
        }
    }

    /**
     * Revoke an OAuth token
     *
     * @throws OAuthException
     */
    public function revokeToken(string $provider, string $token): void
    {
        $this->resolveProvider($provider)->revokeToken($token);

        // Clear any cached data
        Cache::forget("oauth_token_{$provider}_{$token}");
    }

    public function registerProvider(string $name, string $providerClass): void
    {
        if (!is_subclass_of($providerClass, OAuthProviderInterface::class)) {
            throw new \InvalidArgumentException(
                "Provider class must implement " . OAuthProviderInterface::class
            );
        }

        $this->providers[$name] = $providerClass;
    }
}
