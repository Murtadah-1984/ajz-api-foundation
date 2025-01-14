<?php


namespace App\Http\Controllers\Api;

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

//in AppServiceProvider
public function register()
{
    $this->app->singleton(EnhancedCacheService::class);
    $this->app->singleton(RateLimitService::class);
    $this->app->singleton(PerformanceMonitoringService::class);
    $this->app->singleton(SecurityService::class);
}
/**
 * Run Migration
    * php artisan make:migration create_api_metrics_table
    *php artisan make:migration create_rate_limit_tiers_table
 */
// routes/Console.php
Artisan::command('cache:prune-metrics')->daily();
Artisan::command('monitor:check-thresholds')->everyFiveMinutes();

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
CACHE_MISS_THRESHOLD=0.3
QUEUE_LENGTH_THRESHOLD=1000
ALERT_EMAIL=admin@example.com

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


Seed the rate limit tiers:

bashCopyphp artisan db:seed --class=RateLimitTiersSeeder

Consider adding indexes in the future based on your query patterns:

phpCopy// Example of adding an index later if needed
Schema::table('api_metrics', function (Blueprint $table) {
    $table->index(['metric_type', 'created_at']);
});
Usage examples:

Recording metrics:

phpCopyDB::table('api_metrics')->insert([
    'metric_type' => 'cache',
    'metric_name' => 'cache_hits',
    'endpoint' => '/api/products',
    'value' => 1,
    'recorded_at' => now()
]);

Checking rate limits:

phpCopy$apiKey = DB::table('api_keys')
    ->where('key', $request->header('X-API-Key'))
    ->first();

$tier = DB::table('rate_limit_tiers')
    ->where('name', $apiKey->tier)
    ->first();

Aggregating daily metrics:

phpCopy// Example of a daily aggregation job
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
