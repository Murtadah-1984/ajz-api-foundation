<?php

namespace Ajz\ApiBase\Tests\Unit\Services;

use Ajz\ApiBase\Tests\TestCase;
use Ajz\ApiBase\Services\Base\SecurityService;
use Ajz\ApiBase\Exceptions\ApiKeyNotFoundException;
use Illuminate\Support\Facades\DB;

class SecurityServiceTest extends TestCase
{
    private SecurityService $securityService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->securityService = new SecurityService();
    }

    public function test_can_generate_api_key(): void
    {
        $apiKeyData = $this->securityService->generateApiKey("bronze");

        $this->assertArrayHasKey("api_key", $apiKeyData);
        $this->assertArrayHasKey("secret", $apiKeyData);
        $this->assertNotEmpty($apiKeyData["api_key"]);
        $this->assertNotEmpty($apiKeyData["secret"]);

        // Verify key is stored in database
        $this->assertDatabaseHas("api_keys", [
            "key" => $apiKeyData["api_key"],
            "tier" => "bronze"
        ]);
    }

    public function test_can_validate_api_key(): void
    {
        $apiKeyData = $this->securityService->generateApiKey("silver");

        $isValid = $this->securityService->validateApiKey($apiKeyData["api_key"]);

        $this->assertTrue($isValid);
    }

    public function test_can_revoke_api_key(): void
    {
        $apiKeyData = $this->securityService->generateApiKey("gold");

        $revoked = $this->securityService->revokeApiKey($apiKeyData["api_key"]);

        $this->assertTrue($revoked);
        $this->assertFalse($this->securityService->validateApiKey($apiKeyData["api_key"]));
    }

    public function test_throws_exception_for_invalid_api_key(): void
    {
        $this->expectException(ApiKeyNotFoundException::class);

        $this->securityService->validateApiKey("invalid-key");
    }

    public function test_can_verify_webhook_signature(): void
    {
        $payload = ["data" => "test"];
        $secret = "webhook-secret";
        $signature = hash_hmac("sha256", json_encode($payload), $secret);

        $isValid = $this->securityService->verifyWebhookSignature($payload, $signature, $secret);

        $this->assertTrue($isValid);
    }

    public function test_can_verify_request_signature(): void
    {
        $payload = ["data" => "test"];
        $secret = "api-secret";
        $timestamp = time();
        $signature = hash_hmac("sha256", json_encode($payload) . $timestamp, $secret);

        $isValid = $this->securityService->verifySignature($payload, $signature, $secret, $timestamp);

        $this->assertTrue($isValid);
    }

    public function test_rejects_expired_signature(): void
    {
        $payload = ["data" => "test"];
        $secret = "api-secret";
        $timestamp = time() - 301; // Just over 5 minutes old (default TTL is 300)
        $signature = hash_hmac("sha256", json_encode($payload) . $timestamp, $secret);

        $isValid = $this->securityService->verifySignature($payload, $signature, $secret, $timestamp);

        $this->assertFalse($isValid);
    }
}
