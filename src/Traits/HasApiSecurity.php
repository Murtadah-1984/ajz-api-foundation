<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Traits;

use Illuminate\Support\Collection;
use Ajz\ApiBase\Facades\ApiSecurity;

trait HasApiSecurity
{
    /**
     * Generate a new API key
     *
     * @param string $tier API tier level (e.g., 'bronze', 'silver', 'gold')
     * @return array{api_key: string, secret: string, tier: string}
     */
    protected function generateApiKey(string $tier = 'bronze'): array
    {
        return ApiSecurity::generateApiKey($tier);
    }

    /**
     * Validate an API key
     *
     * @param string $apiKey
     * @return bool
     * @throws SecurityException If API key is invalid
     */
    protected function validateApiKey(string $apiKey): bool
    {
        return ApiSecurity::validateApiKey($apiKey);
    }

    /**
     * Generate a request signature
     *
     * @param array<string,mixed> $payload Request payload
     * @param string $secret Secret key
     * @return string Generated signature
     */
    protected function generateSignature(array $payload, string $secret): string
    {
        return ApiSecurity::generateSignature($payload, $secret);
    }

    /**
     * Verify a request signature
     *
     * @param array<string,mixed> $payload Request payload
     * @param string $signature Provided signature
     * @param string $secret Secret key
     * @param string $timestamp Request timestamp
     * @return bool
     * @throws SecurityException If signature is invalid
     */
    protected function verifySignature(array $payload, string $signature, string $secret, string $timestamp): bool
    {
        return ApiSecurity::verifySignature($payload, $signature, $secret, $timestamp);
    }

    /**
     * Generate a webhook HMAC signature
     *
     * @param array<string,mixed> $payload Webhook payload
     * @param string $secret Webhook secret
     * @return string Generated HMAC
     */
    protected function generateWebhookHmac(array $payload, string $secret): string
    {
        return ApiSecurity::generateWebhookHmac($payload, $secret);
    }

    /**
     * Verify a webhook signature
     *
     * @param array<string,mixed> $payload Webhook payload
     * @param string $signature Provided signature
     * @param string $secret Webhook secret
     * @return bool
     * @throws SecurityException If signature is invalid
     */
    protected function verifyWebhookSignature(array $payload, string $signature, string $secret): bool
    {
        return ApiSecurity::verifyWebhookSignature($payload, $signature, $secret);
    }

    /**
     * Store a new webhook secret
     *
     * @param string $identifier Unique identifier for the webhook
     * @param string $secret Secret key
     * @param string|null $description Optional description
     * @return array{identifier: string, secret: string}
     */
    protected function storeWebhookSecret(string $identifier, string $secret, ?string $description = null): array
    {
        return ApiSecurity::storeWebhookSecret($identifier, $secret, $description);
    }

    /**
     * Get a webhook secret by identifier
     *
     * @param string $identifier Webhook identifier
     * @return string Secret key
     * @throws SecurityException If secret not found
     */
    protected function getWebhookSecret(string $identifier): string
    {
        return ApiSecurity::getWebhookSecret($identifier);
    }

    /**
     * List all active webhook secrets
     *
     * @return Collection<int, array{identifier: string, description: string|null}>
     */
    protected function listWebhookSecrets(): Collection
    {
        return ApiSecurity::listWebhookSecrets();
    }

    /**
     * Revoke a webhook secret
     *
     * @param string $identifier Webhook identifier
     * @return bool True if revoked, false if not found
     */
    protected function revokeWebhookSecret(string $identifier): bool
    {
        return ApiSecurity::revokeWebhookSecret($identifier);
    }

    /**
     * Revoke an API key
     *
     * @param string $apiKey API key to revoke
     * @return bool True if revoked, false if not found
     */
    protected function revokeApiKey(string $apiKey): bool
    {
        return ApiSecurity::revokeApiKey($apiKey);
    }

    /**
     * Get the secret for an API key
     *
     * @param string $apiKey API key
     * @return string|null Secret if found, null otherwise
     */
    protected function getApiKeySecret(string $apiKey): ?string
    {
        return ApiSecurity::getWebhookSecret($apiKey);
    }

    /**
     * Rotate an API key with a new one of the same tier
     *
     * @param string $apiKey Current API key
     * @return array{api_key: string, secret: string, tier: string} New API key credentials
     * @throws ApiKeyNotFoundException If API key not found
     */
    protected function rotateApiKey(string $apiKey): array
    {
        return ApiSecurity::rotateApiKey($apiKey);
    }
}
