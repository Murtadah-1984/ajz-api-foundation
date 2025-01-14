<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Services\Base;

use Illuminate\Support\Facades\{Hash,Cache,DB,Log};
use Illuminate\Support\{Str,Collection};
use Carbon\Carbon;
use Ajz\ApiBase\Contracts\SecurityServiceInterface;
use Ajz\ApiBase\Exceptions\{SecurityException, ApiKeyNotFoundException};

final class SecurityService implements SecurityServiceInterface
{
    /**
     * Store a new webhook secret
     *
     * @param string $identifier
     * @param string $secret
     * @param string|null $description
     * @return array{identifier: string, secret: string}
     */
    public function storeWebhookSecret(string $identifier, string $secret, ?string $description = null): array
    {
        DB::table('webhook_secrets')->insert([
            'identifier' => $identifier,
            'secret' => Hash::make($secret),
            'description' => $description,
            'created_at' => now(),
        ]);

        Log::info('Webhook secret stored', ['identifier' => $identifier]);

        return [
            'identifier' => $identifier,
            'secret' => $secret,
        ];
    }

    /**
     * Get webhook secret by identifier
     *
     * @param string $identifier
     * @return string
     * @throws SecurityException
     */
    public function getWebhookSecret(string $identifier): string
    {
        $secret = Cache::remember(
            "webhook_secret:{$identifier}",
            300,
            fn () => DB::table('webhook_secrets')
                ->where('identifier', $identifier)
                ->where('is_active', true)
                ->value('secret')
        );

        if (!$secret) {
            Log::warning('Webhook secret not found', ['identifier' => $identifier]);
            throw SecurityException::missingWebhookSecret($identifier);
        }

        return $secret;
    }

    /**
     * List all active webhook secrets
     *
     * @return Collection<int, array{identifier: string, description: string|null}>
     */
    public function listWebhookSecrets(): Collection
    {
        return DB::table('webhook_secrets')
            ->where('is_active', true)
            ->get(['identifier', 'description'])
            ->map(fn ($row) => [
                'identifier' => $row->identifier,
                'description' => $row->description,
            ]);
    }

    /**
     * Revoke a webhook secret
     *
     * @param string $identifier
     * @return bool
     */
    public function revokeWebhookSecret(string $identifier): bool
    {
        $updated = DB::table('webhook_secrets')
            ->where('identifier', $identifier)
            ->update(['is_active' => false]);

        if ($updated) {
            Cache::forget("webhook_secret:{$identifier}");
            Log::info('Webhook secret revoked', ['identifier' => $identifier]);
        }

        return $updated > 0;
    }

    /**
     * Generate API signature for request
     *
     * @param array<string,mixed> $payload
     * @param string $secret
     * @param string|null $timestamp
     * @return string
     */
    public function generateSignature(array $payload, string $secret, ?string $timestamp = null): string
    {
        $timestamp = $timestamp ?? now()->timestamp;
        $dataToSign = json_encode($payload) . $timestamp;
        return hash_hmac('sha256', $dataToSign, $secret);
    }

    /**
     * Verify request signature
     *
     * @param array<string,mixed> $payload
     * @param string $signature
     * @param string $secret
     * @param string $timestamp
     * @return bool
     * @throws SecurityException
     */
    public function verifySignature(array $payload, string $signature, string $secret, string $timestamp): bool
    {
        $expectedSignature = $this->generateSignature($payload, $secret, $timestamp);
        $isValid = hash_equals($signature, $expectedSignature);

        if (!$isValid) {
            Log::warning('Invalid signature detected', [
                'payload' => json_encode($payload),
                'timestamp' => $timestamp
            ]);
            throw SecurityException::invalidSignature();
        }

        return true;
    }

    /**
     * Generate new API key
     *
     * @param string $tier
     * @return array{api_key: string, secret: string, tier: string}
     */
    public function generateApiKey(string $tier = 'bronze'): array
    {
        $apiKey = Str::random(32);
        $secret = Str::random(64);

        DB::table('api_keys')->insert([
            'key' => $apiKey,
            'secret' => Hash::make($secret),
            'tier' => $tier,
            'created_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        Log::info('New API key generated', ['tier' => $tier]);

        return [
            'api_key' => $apiKey,
            'secret' => $secret,
            'tier' => $tier
        ];
    }

    /**
     * Validate API key
     *
     * @param string $apiKey
     * @return bool
     * @throws SecurityException
     */
    public function validateApiKey(string $apiKey): bool
    {
        $isValid = Cache::remember("api_key:{$apiKey}", 300, function () use ($apiKey) {
            return DB::table('api_keys')
                ->where('key', $apiKey)
                ->where('is_active', true)
                ->where('expires_at', '>', now())
                ->exists();
        });

        if (!$isValid) {
            Log::warning('Invalid API key used', ['key' => $apiKey]);
            throw SecurityException::invalidApiKey();
        }

        return true;
    }

    /**
     * Revoke API key
     *
     * @param string $apiKey
     * @return bool
     */
    public function revokeApiKey(string $apiKey): bool
    {
        $updated = DB::table('api_keys')
            ->where('key', $apiKey)
            ->update([
                'is_active' => false,
                'deleted_at' => now(),
                'deleted_by' => auth()->id()
            ]);

        if ($updated) {
            Cache::forget("api_key:{$apiKey}");
            Log::info('API key revoked', ['key' => $apiKey]);
        }

        return $updated > 0;
    }

    /**
     * Rotate API key with a new one of the same tier
     *
     * @param string $apiKey Current API key
     * @return array{api_key: string, secret: string, tier: string}
     * @throws ApiKeyNotFoundException If API key not found
     */
    public function rotateApiKey(string $apiKey): array
    {
        $currentKey = DB::table('api_keys')
            ->where('key', $apiKey)
            ->where('is_active', true)
            ->first(['tier']);

        if (!$currentKey) {
            Log::warning('Attempt to rotate non-existent API key', ['key' => $apiKey]);
            throw ApiKeyNotFoundException::forKey($apiKey);
        }

        $newCredentials = $this->generateApiKey($currentKey->tier);
        $this->revokeApiKey($apiKey);

        Log::info('API key rotated', [
            'old_key' => $apiKey,
            'tier' => $currentKey->tier
        ]);

        return $newCredentials;
    }

    /**
     * Generate HMAC for webhook
     *
     * @param array<string,mixed> $payload
     * @param string $secret
     * @return string
     */
    public function generateWebhookHmac(array $payload, string $secret): string
    {
        return hash_hmac('sha256', json_encode($payload), $secret);
    }

    /**
     * Verify webhook signature
     *
     * @param array<string,mixed> $payload
     * @param string $signature
     * @param string $secret
     * @return bool
     * @throws SecurityException
     */
    public function verifyWebhookSignature(array $payload, string $signature, string $secret): bool
    {
        $expectedSignature = $this->generateWebhookHmac($payload, $secret);
        $isValid = hash_equals($signature, $expectedSignature);

        if (!$isValid) {
            Log::warning('Invalid webhook signature detected', [
                'payload' => json_encode($payload)
            ]);
            throw SecurityException::invalidWebhookSignature();
        }

        return true;
    }

    /**
     * @deprecated Use EnhancedApiRateLimit middleware instead
     * @see \Ajz\ApiBase\Http\Middleware\EnhancedApiRateLimit
     */
    public function checkRateLimit(string $apiKey, string $endpoint): bool
    {
        trigger_error(
            'Method ' . __METHOD__ . ' is deprecated. Use EnhancedApiRateLimit middleware instead.',
            E_USER_DEPRECATED
        );

        return true;
    }
}
