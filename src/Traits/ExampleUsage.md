// Example usage within the package (src/Http/Controllers/ApiKeyController.php)
namespace VendorName\ApiBase\Http\Controllers;

use Illuminate\Http\Request;
use VendorName\ApiBase\Facades\ApiSecurity;

class ApiKeyController extends Controller
{
    public function generate(Request $request)
    {
        $tier = $request->input('tier', 'bronze');
        $apiKeyData = ApiSecurity::generateApiKey($tier);

        return response()->json([
            'message' => 'API key generated successfully',
            'data' => $apiKeyData
        ]);
    }

    public function revoke(Request $request)
    {
        $apiKey = $request->input('api_key');
        $revoked = ApiSecurity::revokeApiKey($apiKey);

        return response()->json([
            'message' => $revoked ? 'API key revoked successfully' : 'API key not found',
            'success' => $revoked
        ]);
    }
}

// Example usage outside the package (in your Laravel application)
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use VendorName\ApiBase\Facades\ApiSecurity;
use VendorName\ApiBase\Traits\HasApiSecurity;

class PaymentController extends Controller
{
    use HasApiSecurity;

    public function processWebhook(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('X-Webhook-Signature');
        $secret = config('services.payment.webhook_secret');

        if (!ApiSecurity::verifyWebhookSignature($payload, $signature, $secret)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Process webhook...
        return response()->json(['message' => 'Webhook processed']);
    }

    public function createApiClient(Request $request)
    {
        // Generate new API credentials
        $apiCredentials = $this->generateApiKey('silver');

        return response()->json([
            'message' => 'API client created successfully',
            'credentials' => $apiCredentials
        ]);
    }
}

// Example middleware usage (in your Laravel application)
namespace App\Http\Middleware;

use Closure;
use VendorName\ApiBase\Facades\ApiSecurity;

class ValidateApiSignature
{
    public function handle($request, Closure $next)
    {
        $payload = $request->all();
        $signature = $request->header('X-API-Signature');
        $timestamp = $request->header('X-Timestamp');
        $apiKey = $request->header('X-API-Key');

        // Get secret from database based on API key
        $secret = $this->getApiKeySecret($apiKey);

        if (!ApiSecurity::verifySignature($payload, $signature, $secret, $timestamp)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        return $next($request);
    }

    private function getApiKeySecret($apiKey)
    {
        // Implement fetching secret for the API key
        return 'your-secret';
    }
}

Inside Your Package:

phpCopy// Register facades in your ServiceProvider
public function register()
{
    $this->app->singleton('api-base.security', function ($app) {
        return new SecurityService();
    });

    $this->app->singleton('api-base.monitor', function ($app) {
        return new MonitoringService();
    });
}

// Register aliases
public function boot()
{
    $this->app->alias('api-base.security', ApiSecurity::class);
    $this->app->alias('api-base.monitor', ApiMonitor::class);
}

Configuration Setup:
Add to your package's config file:

phpCopy// config/api-base/security.php
return [
    'signature_ttl' => env('API_SIGNATURE_TTL', 300), // 5 minutes
    'key_expiry' => env('API_KEY_EXPIRY', 31536000), // 1 year
    'default_tier' => env('API_DEFAULT_TIER', 'bronze'),
];

Usage Examples Outside Package:

a. Basic API Security:
phpCopyuse VendorName\ApiBase\Facades\ApiSecurity;

class ApiController extends Controller
{
    public function secureEndpoint(Request $request)
    {
        // Validate API key
        if (!ApiSecurity::validateApiKey($request->header('X-API-Key'))) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        // Your code here...
    }
}
b. Using Traits:
phpCopyuse VendorName\ApiBase\Traits\HasApiSecurity;

class ClientController extends Controller
{
    use HasApiSecurity;

    public function createClient()
    {
        $credentials = $this->generateApiKey('silver');
        // Store or send credentials...
    }
}
c. Webhook Handling:
phpCopyuse VendorName\ApiBase\Facades\ApiSecurity;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('X-Webhook-Signature');

        if (!ApiSecurity::verifyWebhookSignature($payload, $signature, config('services.webhook.secret'))) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Process webhook...
    }
}

Using with Route Middleware:

phpCopy// routes/api.php
Route::middleware(['api.signed'])->group(function () {
    Route::post('/webhook', [WebhookController::class, 'handle']);
});

Client-Side Implementation Example:

phpCopy// Example of how clients should sign requests
$payload = ['data' => 'example'];
$timestamp = time();
$signature = hash_hmac('sha256', json_encode($payload) . $timestamp, 'your-secret');

$response = Http::withHeaders([
    'X-API-Key' => 'your-api-key',
    'X-Timestamp' => $timestamp,
    'X-Signature' => $signature
])->post('api/endpoint', $payload);

Monitoring Usage:

phpCopyuse VendorName\ApiBase\Facades\ApiMonitor;

class ProductController extends Controller
{
    public function index()
    {
        $startTime = microtime(true);

        // Your code here...

        ApiMonitor::recordMetrics('endpoint.performance', [
            'endpoint' => 'products.index',
            'duration' => microtime(true) - $startTime,
            'memory' => memory_get_peak_usage(true)
        ]);
    }
}

Using in Controllers:

phpCopyuse VendorName\ApiBase\Traits\HasApiCache;
use VendorName\ApiBase\Traits\HasApiMonitoring;

class YourController extends Controller
{
    use HasApiCache, HasApiMonitoring;

    public function index()
    {
        // Use caching
        $data = $this->remember('key', fn() => Model::get());

        // Use monitoring
        $this->recordMetrics('type', ['key' => 'value']);
    }
}

Configuration:
The package publishes several config files:


config/api-base/cache.php
config/api-base/monitor.php
config/api-base/rate-limit.php


Environment Variables:
Add these to your .env:

envCopyAPI_CACHE_PREFIX=api_cache:
API_CACHE_TTL=3600
API_CACHE_VERSION=v1
API_MONITOR_ENABLED=true

Additional Files Needed:

a. Add a README.md:
markdownCopy# API Base Package

Laravel package for API caching, monitoring, and rate limiting.

## Installation

```bash
composer require your-vendor/api-base
php artisan api-base:install
Usage
...
Copy
b. Add a LICENSE file (e.g., MIT License)

c. Add `.gitignore`:
/vendor
.idea
.phpunit.result.cache
composer.lock
Copy
7. Publishing Package:
```bash
git init
git add .
git commit -m "Initial commit"
git tag v1.0.0

Testing Setup:
Add phpunit.xml:

xmlCopy<?xml version="1.0" encoding="UTF-8"?>
<phpunit>
    <!-- Your PHPUnit configuration -->
</phpunit>
To make everything work together:

Register the service provider in your app's config/app.php:

phpCopy'providers' => [
    // ...
    VendorName\ApiBase\Providers\ApiBaseServiceProvider::class,
],

'aliases' => [
    // ...
    'ApiCache' => VendorName\ApiBase\Facades\ApiCache::class,
    'ApiMonitor' => VendorName\ApiBase\Facades\ApiMonitor::class,
]

Publish and run migrations:

bashCopyphp artisan vendor:publish --provider="VendorName\ApiBase\Providers\ApiBaseServiceProvider"
php artisan migrate
