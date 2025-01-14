<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Repositories\Interfaces\Auth;

use MyDDD\AuthDomain\DataTransferObjects\Auth\OAuth\OAuthUserDTO;
use MyDDD\AuthDomain\DataTransferObjects\Auth\OAuth\TokenDTO;

interface OAuthProviderInterface
{
    /**
     * Get the authorization URL for the provider
     */
    public function getAuthorizationUrl(): string;

    /**
     * Get user details from provider
     */
    public function getUserByCode(string $code): OAuthUserDTO;

    /**
     * Exchange authorization code for access token
     */
    public function getAccessToken(string $code): TokenDTO;

    /**
     * Get the provider name
     */
    public function getProviderName(): string;

    /**
     * Get the provider's client ID
     */
    public function getClientId(): string;

    /**
     * Get the provider's client secret
     */
    public function getClientSecret(): string;

    /**
     * Get the provider's redirect URL
     */
    public function getRedirectUrl(): string;

    /**
     * Get the provider's scopes
     *
     * @return array<string>
     */
    public function getScopes(): array;

    /**
     * Revoke an access token
     *
     * @throws \App\Domains\Auth\OAuth\Exceptions\OAuthException
     */
    public function revokeToken(string $token): void;
}
