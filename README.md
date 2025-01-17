# Laravel API Foundation

A comprehensive Laravel package designed to scaffold and bootstrap API applications with industry-standard practices and patterns. Built for Laravel 11 and PHP 8.3, this package provides a robust foundation for building scalable, maintainable, and secure API applications.

## Features

- **Docker Integration**: Pre-configured Docker environment optimized for Laravel API development
- **Package Integration**: Automated installation and configuration of essential packages
- **Architecture Patterns**: Implementation of SOLID principles and common design patterns
- **API Versioning**: Built-in support for API versioning
- **Rate Limiting**: Configurable rate limiting strategies
- **Caching**: Smart caching implementation for optimal performance
- **Queue Management**: Pre-configured queue workers and job handling
- **Documentation**: Automated API documentation generation
- **Security**: Built-in security best practices and middleware
- **Testing**: Pre-configured testing environment and templates

## Requirements

- PHP 8.3+
- Laravel 11.x
- Docker & Docker Compose
- Composer 2.x

## Available Traits

### HasApiCache

Provides caching functionality for API responses.

```php
use Ajz\ApiBase\Traits\HasApiCache;

class YourController extends Controller
{
    use HasApiCache;

    public function index()
    {
        // Cache data with a specific key
        $data = $this->remember('products', function() {
            return Product::all();
        }, 3600); // Cache for 1 hour

        // Cache with tags
        $data = $this->remember('user.1.posts', function() {
            return Post::where('user_id', 1)->get();
        }, 3600, ['users', 'posts']);

        // Remove specific cache
        $this->forget('products');

        // Work with cache tags
        $posts = $this->tags(['posts'])->remember('all.posts', function() {
            return Post::all();
        });
    }
}
```

### HasApiMonitoring

Provides monitoring capabilities for API endpoints.

```php
use Ajz\ApiBase\Traits\HasApiMonitoring;

class YourController extends Controller
{
    use HasApiMonitoring;

    public function index()
    {
        // Record custom metrics
        $this->recordMetrics('api.request', [
            'endpoint' => 'products.index',
            'duration' => 150, // milliseconds
            'status' => 200
        ]);

        // Get queue metrics
        $queueMetrics = $this->getQueueMetrics();
    }
}
```

### HasApiSecurity

Provides comprehensive security features for API authentication and authorization.

```php
use Ajz\ApiBase\Traits\HasApiSecurity;

class YourController extends Controller
{
    use HasApiSecurity;

    public function generateKey()
    {
        // Generate new API key with tier
        $credentials = $this->generateApiKey('silver');
        // Returns: ['api_key' => '...', 'secret' => '...', 'tier' => 'silver']
    }

    public function validateRequest()
    {
        // Validate API key
        $isValid = $this->validateApiKey($request->header('X-API-Key'));

        // Generate request signature
        $signature = $this->generateSignature($payload, $secret);

        // Verify request signature
        $isValid = $this->verifySignature(
            $payload,
            $request->header('X-Signature'),
            $secret,
            $request->header('X-Timestamp')
        );
    }

    public function handleWebhook()
    {
        // Generate webhook HMAC
        $hmac = $this->generateWebhookHmac($payload, $webhookSecret);

        // Verify webhook signature
        $isValid = $this->verifyWebhookSignature(
            $payload,
            $request->header('X-Webhook-Signature'),
            $webhookSecret
        );

        // Store webhook secret
        $secret = $this->storeWebhookSecret('payment-webhook', 'secret123', 'Payment processing webhook');

        // Get webhook secret
        $secret = $this->getWebhookSecret('payment-webhook');

        // List all webhook secrets
        $secrets = $this->listWebhookSecrets();

        // Revoke webhook secret
        $revoked = $this->revokeWebhookSecret('payment-webhook');
    }

    public function manageApiKeys()
    {
        // Revoke API key
        $revoked = $this->revokeApiKey('api-key-123');

        // Get API key secret
        $secret = $this->getApiKeySecret('api-key-123');

        // Rotate API key
        $newCredentials = $this->rotateApiKey('api-key-123');
        // Returns new API key credentials of same tier
    }
}
```

## Available Facades

### ApiCache

Provides static access to caching functionality.

```php
use Ajz\ApiBase\Facades\ApiCache;

// Cache data
$data = ApiCache::remember('key', function() {
    return Product::all();
}, 3600);

// Cache with tags
$data = ApiCache::tags(['users'])->remember('user.posts', function() {
    return Post::where('user_id', 1)->get();
});

// Remove cache
ApiCache::forget('key');
```

### ApiMonitor

Provides static access to monitoring functionality.

```php
use Ajz\ApiBase\Facades\ApiMonitor;

// Record metrics
ApiMonitor::recordMetrics('api.performance', [
    'endpoint' => 'users.show',
    'duration' => 100,
    'memory' => memory_get_peak_usage(true)
]);

// Get queue metrics
$metrics = ApiMonitor::getQueueMetrics();
```

### ApiSecurity

Provides static access to security functionality.

```php
use Ajz\ApiBase\Facades\ApiSecurity;

// Generate API key
$credentials = ApiSecurity::generateApiKey('gold');

// Validate API key
$isValid = ApiSecurity::validateApiKey($apiKey);

// Generate signature
$signature = ApiSecurity::generateSignature($payload, $secret);

// Verify signature
$isValid = ApiSecurity::verifySignature($payload, $signature, $secret, $timestamp);

// Webhook operations
$hmac = ApiSecurity::generateWebhookHmac($payload, $secret);
$isValid = ApiSecurity::verifyWebhookSignature($payload, $signature, $secret);
$secret = ApiSecurity::storeWebhookSecret('webhook-id', 'secret', 'description');
$secret = ApiSecurity::getWebhookSecret('webhook-id');
$secrets = ApiSecurity::listWebhookSecrets();
$revoked = ApiSecurity::revokeWebhookSecret('webhook-id');

// API key management
$revoked = ApiSecurity::revokeApiKey($apiKey);
$secret = ApiSecurity::getApiKeySecret($apiKey);
$newCredentials = ApiSecurity::rotateApiKey($apiKey);
```
```php
// In your controller
public function show($id)
{
    return $this->cacheService->remember(
        "product:{$id}",
        fn() => Product::findOrFail($id),
        3600,
        ['products', "product-{$id}"]
    );
}

//.env
//CACHE_MISS_THRESHOLD=0.3
//QUEUE_LENGTH_THRESHOLD=1000
//ALERT_EMAIL=admin@example.com

// In your routes/api.php
Route::middleware(['api.limit:auth'])
    ->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
    });

// Set up monitoring in your service layer
$this->monitoringService->recordMetrics('cache', [
    'key' => 'product_fetch',
    'value' => $duration
]);

// Verify signed requests
if (!$this->securityService->verifyRequestSignature($request)) {
    return response()->json(['error' => 'Invalid signature'], 401);
}


//Seed the rate limit tiers:

//bashCopyphp artisan db:seed --class=RateLimitTiersSeeder

//Consider adding indexes in the future based on your query patterns:

//phpCopy// Example of adding an index later if needed
Schema::table('api_metrics', function (Blueprint $table) {
    $table->index(['metric_type', 'created_at']);
});
//Recording metrics:

phpCopyDB::table('api_metrics')->insert([
    'metric_type' => 'cache',
    'metric_name' => 'cache_hits',
    'endpoint' => '/api/products',
    'value' => 1,
    'recorded_at' => now()
]);

//Checking rate limits:

//phpCopy$apiKey = DB::table('api_keys')
    ->where('key', $request->header('X-API-Key'))
    ->first();

$tier = DB::table('rate_limit_tiers')
    ->where('name', $apiKey->tier)
    ->first();

//Aggregating daily metrics:

//phpCopy// Example of a daily aggregation job
DB::table('api_metrics')
    ->select(
        'metric_type',
        'metric_name',
        'endpoint',
        DB::raw('MIN(value) as min_value'),
        DB::raw('MAX(value) as max_value'),
        DB::raw('AVG(value) as avg_value'),
        DB::raw('COUNT(*) as count')
    )
    ->whereDate('created_at', now()->subDay())
    ->groupBy('metric_type', 'metric_name', 'endpoint')
    ->get()
    ->each(function ($metric) {
        DB::table('api_metrics_daily')->insert([
            'metric_type' => $metric->metric_type,
            'metric_name' => $metric->metric_name,
            'endpoint' => $metric->endpoint,
            'min_value' => $metric->min_value,
            'max_value' => $metric->max_value,
            'avg_value' => $metric->avg_value,
            'count' => $metric->count,
            'date' => now()->subDay()->toDateString()
        ]);
    });

use Ajz\ApiBase\Services\Base\EnhancedCacheService;
use Ajz\ApiBase\Services\Base\PerformanceMonitoringService;
use Ajz\ApiBase\Services\Base\SecurityService;

class ProductController extends Controller
{
    protected $cacheService;
    protected $monitoringService;
    protected $securityService;

    public function __construct(
        EnhancedCacheService $cacheService,
        PerformanceMonitoringService $monitoringService,
        SecurityService $securityService
    ) {
        $this->cacheService = $cacheService;
        $this->monitoringService = $monitoringService;
        $this->securityService = $securityService;
    }

    public function index()
    {
        // Verify request signature for sensitive endpoints
        if (!$this->securityService->verifyRequestSignature(request())) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $startTime = microtime(true);

        $products = $this->cacheService->remember('products:all', function () {
            return Product::with(['category', 'variants', 'prices'])
                ->active()
                ->latest()
                ->get();
        }, null, ['products']);

        $duration = microtime(true) - $startTime;
        $this->monitoringService->recordMetrics('cache', [
            'key' => 'products_fetch',
            'value' => $duration
        ]);

        return response()->json($products);
    }
}
```
## License

The MIT License (MIT)
