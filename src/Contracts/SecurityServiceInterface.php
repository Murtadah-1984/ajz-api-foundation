<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Contracts;

use Illuminate\Support\Collection;

interface SecurityServiceInterface
{
    /**
     * Store a new webhook secret
     *
     * @param string $identifier
     * @param string $secret
     * @param string|null $description
     * @return array{identifier: string, secret: string}
     */
    public function storeWebhookSecret(string $identifier, string $secret, ?string $description = null): array;

    /**
     * Get webhook secret by identifier
     *
     * @param string $identifier
     * @return string
     * @throws SecurityException
     */
    public function getWebhookSecret(string $identifier): string;

    /**
     * List all active webhook secrets
     *
     * @return Collection<int, array{identifier: string, description: string|null}>
     */
    public function listWebhookSecrets(): Collection;

    /**
     * Revoke a webhook secret
     *
     * @param string $identifier
     * @return bool
     */
    public function revokeWebhookSecret(string $identifier): bool;

    /**
     * Generate API signature for request
     *
     * @param array<string,mixed> $payload
     * @param string $secret
     * @param string|null $timestamp
     * @return string
     */
    public function generateSignature(array $payload, string $secret, ?string $timestamp = null): string;

    /**
     * Verify request signature
     *
     * @param array<string,mixed> $payload
     * @param string $signature
     * @param string $secret
     * @param string $timestamp
     * @return bool
     */
    public function verifySignature(array $payload, string $signature, string $secret, string $timestamp): bool;

    /**
     * Generate new API key
     *
     * @param string $tier
     * @return array{api_key: string, secret: string, tier: string}
     */
    public function generateApiKey(string $tier = 'bronze'): array;

    /**
     * Validate API key
     *
     * @param string $apiKey
     * @return bool
     */
    public function validateApiKey(string $apiKey): bool;

    /**
     * Revoke API key
     *
     * @param string $apiKey
     * @return bool
     */
    public function revokeApiKey(string $apiKey): bool;

    /**
     * Rotate API key with a new one of the same tier
     *
     * @param string $apiKey Current API key
     * @return array{api_key: string, secret: string, tier: string}
     * @throws SecurityException If API key not found
     */
    public function rotateApiKey(string $apiKey): array;

    /**
     * Generate HMAC for webhook
     *
     * @param array<string,mixed> $payload
     * @param string $secret
     * @return string
     */
    public function generateWebhookHmac(array $payload, string $secret): string;

    /**
     * Verify webhook signature
     *
     * @param array<string,mixed> $payload
     * @param string $signature
     * @param string $secret
     * @return bool
     */
    public function verifyWebhookSignature(array $payload, string $signature, string $secret): bool;
}
