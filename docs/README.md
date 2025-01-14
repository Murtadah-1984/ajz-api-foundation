# Laravel API Foundation Documentation

## Table of Contents

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [API Security](#api-security)
4. [Rate Limiting](#rate-limiting)
5. [Webhooks](#webhooks)
6. [Best Practices](#best-practices)
7. [Upgrade Guide](#upgrade-guide)

## Installation

```bash
composer require ajz/laravel-api-foundation

# Publish configuration and migrations
php artisan vendor:publish --provider="Ajz\ApiBase\ApiBaseServiceProvider"

# Run migrations
php artisan migrate
```

## Configuration

### API Base Configuration
```php
// config/api-base.php
return [
    'security' => [
        'key_rotation_days' => env('API_KEY_ROTATION_DAYS', 365),
        'signature_ttl' => env('API_SIGNATURE_TTL', 300),
    ],
    'rate_limit' => [
        'default_limit' => env('API_RATE_LIMIT_DEFAULT', 60),
        'default_burst' => env('API_RATE_LIMIT_BURST', 5),
    ]
];
```

## API Security

### API Key Management

```php
use Ajz\ApiBase\Traits\HasApiSecurity;

class ApiController extends Controller
{
    use HasApiSecurity;

    public function createApiKey()
    {
        $credentials = $this->generateApiKey('silver');
        return response()->json($credentials);
    }
}
```

### Request Signing

All API requests should include:
- `X-API-Key`: Your API key
- `X-Timestamp`: Current Unix timestamp
- `X-Signature`: Request signature

Example signature generation:
```php
$payload = ['data' => 'example'];
$timestamp = time();
$signature = hash_hmac(
    'sha256',
    json_encode($payload) . $timestamp,
    $secret
);
```

## Rate Limiting

### Headers

The API returns the following rate limit headers:
- `X-RateLimit-Limit`: Maximum requests per minute
- `X-RateLimit-Remaining`: Remaining requests in window
- `X-RateLimit-Reset`: Timestamp when the limit resets

### Rate Limit Tiers

Default tiers:
- Bronze: 60 requests/minute
- Silver: 300 requests/minute
- Gold: 1000 requests/minute

Configure middleware:
```php
Route::middleware(['api.rate-limit:gold'])->group(function () {
    // Routes here
});
```

## Webhooks

### Webhook Security

Webhook requests include:
- `X-Webhook-Signature`: HMAC signature of payload
- Payload in JSON format

Verify webhooks:
```php
public function handleWebhook(Request $request)
{
    $payload = $request->all();
    $signature = $request->header('X-Webhook-Signature');
    $secret = $this->getWebhookSecret('payment-webhook');

    if (!$this->verifyWebhookSignature($payload, $signature, $secret)) {
        return response()->json(['error' => 'Invalid signature'], 401);
    }

    // Process webhook...
}
```

## Best Practices

### Security Best Practices

1. API Key Management
   - Rotate API keys regularly (recommended: every 90 days)
   - Use appropriate tier levels based on client needs
   - Store API secrets securely (never in version control)

2. Request Signing
   - Always validate timestamps to prevent replay attacks
   - Use constant-time comparison for signatures
   - Include all relevant request data in signature

3. Rate Limiting
   - Set appropriate limits per tier
   - Monitor rate limit usage
   - Implement gradual backoff

4. Webhook Security
   - Use unique secrets per webhook integration
   - Validate signatures before processing
   - Implement retry logic with exponential backoff

### Implementation Best Practices

1. Exception Handling
```php
try {
    $this->validateApiKey($apiKey);
} catch (SecurityException $e) {
    Log::warning('Security violation', [
        'error' => $e->getMessage(),
        'key' => $apiKey
    ]);
    return response()->json(['error' => 'Unauthorized'], 401);
}
```

2. Logging
```php
Log::info('API key rotated', [
    'old_key' => $oldKey,
    'new_key' => $newKey,
    'tier' => $tier
]);
```

## Upgrade Guide

### Upgrading to 2.0

1. Database Changes
   - New columns added to api_keys table
   - New webhook_secrets table
   ```bash
   php artisan migrate
   ```

2. Breaking Changes
   - Signature verification now throws exceptions
   - Rate limiting moved to middleware
   - New webhook management features

3. New Features
   - API key rotation
   - Webhook secret management
   - Enhanced rate limiting
   - Audit logging

4. Deprecations
   - `checkRateLimit` method deprecated in favor of middleware
   - Old signature format deprecated

### Testing

Run the test suite:
```bash
php artisan test --filter=ApiSecurity
```

Example test cases:
```php
public function test_api_key_rotation()
{
    $oldKey = $this->generateApiKey('silver');
    $newKey = $this->rotateApiKey($oldKey['api_key']);
    
    $this->assertNotEquals($oldKey['api_key'], $newKey['api_key']);
    $this->assertEquals($oldKey['tier'], $newKey['tier']);
}
