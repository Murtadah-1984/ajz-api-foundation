<?php

namespace MyDDD\AuthDomain\Repositories\Interfaces\Auth;

use MyDDD\AuthDomain\DataTransferObjects\Auth\TokenData;

interface TokenRepositoryInterface
{
    /**
     * Find a token by its value
     */
    public function findToken(string $token): ?TokenData;

    /**
     * Refresh a token using a refresh token
     */
    public function refreshToken(string $refreshToken): TokenData;

    /**
     * Revoke a token
     */
    public function revokeToken(string $token): void;

    /**
     * Store a token
     */
    public function storeToken(TokenData $tokenData): void;
}
