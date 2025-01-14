# Laravel API Foundation
## Technical Project Overview

### Core Architecture & Design Principles

#### Technical Requirements
- PHP 8.3+
- Laravel 11.x
- Strict typing with `declare(strict_types=1)`
- PSR-12 coding standards
- SOLID principles adherence
- Interface segregation
- Dependency injection

#### Core Dependencies
```json
{
    "require": {
        "php": "^8.3",
        "laravel/framework": "^11.0",
        "laravel/sanctum": "^4.0",
        "laravel/telescope": "^5.0",
        "laravel/horizon": "^5.0",
        "laravel/passport": "^12.0",
        "prometheus/client_php": "^2.0",
        "sentry/sentry-laravel": "^4.0"
    }
}
```

### Architectural Patterns Implementation

#### 1. Factory Pattern (Provider Selection)
```php
declare(strict_types=1);

namespace LaravelApiFoundation\Factories;

final class ProviderFactory
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly LoggerInterface $logger
    ) {}

    public function create(string $type): ProviderInterface 
    {
        return match ($type) {
            'rest' => $this->container->make(RestApiProvider::class),
            'graphql' => $this->container->make(GraphQLProvider::class),
            default => throw new UnsupportedProviderException($type)
        };
    }
}
```

#### 2. Repository Pattern (Data Layer)
```php
declare(strict_types=1);

namespace LaravelApiFoundation\Repositories;

interface ApiRepositoryInterface
{
    public function find(int $id): ?ApiResponseDTO;
    public function create(ApiRequestDTO $dto): ApiResponseDTO;
    public function update(int $id, ApiRequestDTO $dto): ApiResponseDTO;
    public function delete(int $id): bool;
}

final readonly class ApiRepository implements ApiRepositoryInterface
{
    public function __construct(
        private PDO $connection,
        private CacheManager $cache,
        private LoggerInterface $logger
    ) {}

    public function find(int $id): ?ApiResponseDTO 
    {
        return $this->cache->remember(
            "api.response.{$id}",
            3600,
            fn() => $this->findFromDatabase($id)
        );
    }
}
```

#### 3. Strategy Pattern (API Implementation)
```php
declare(strict_types=1);

namespace LaravelApiFoundation\Strategies;

interface ApiStrategyInterface
{
    public function execute(RequestDTO $request): ResponseDTO;
    public function supports(string $type): bool;
}

final readonly class RestApiStrategy implements ApiStrategyInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private RequestTransformer $transformer,
        private LoggerInterface $logger
    ) {}
}
```

#### 4. Manager Pattern (Result Aggregation)
```php
declare(strict_types=1);

namespace LaravelApiFoundation\Managers;

final readonly class ApiManager
{
    public function __construct(
        private readonly ProviderFactory $providerFactory,
        private readonly ResultAggregator $aggregator,
        private readonly MetricsCollector $metrics,
        private readonly LoggerInterface $logger
    ) {}

    public function process(ApiRequestDTO $request): ApiResponseDTO
    {
        try {
            DB::beginTransaction();
            
            $result = $this->processRequest($request);
            
            DB::commit();
            return $result;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logger->error('API processing failed', [
                'exception' => $e,
                'request' => $request
            ]);
            throw new ApiProcessingException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
```

### Data Transfer & Value Objects

#### 1. Request/Response DTOs
```php
declare(strict_types=1);

namespace LaravelApiFoundation\DTOs;

final readonly class ApiRequestDTO
{
    public function __construct(
        public string $endpoint,
        public string $method,
        public array $parameters,
        public array $headers
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            endpoint: $request->validated('endpoint'),
            method: $request->validated('method'),
            parameters: $request->validated('parameters', []),
            headers: $request->validated('headers', [])
        );
    }
}
```

#### 2. Value Objects
```php
declare(strict_types=1);

namespace LaravelApiFoundation\ValueObjects;

final readonly class ApiEndpoint
{
    private function __construct(
        public string $value
    ) {
        $this->validate($value);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    private function validate(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new InvalidEndpointException($value);
        }
    }
}
```

### Service Layer Implementation

#### 1. Single Action Services
```php
declare(strict_types=1);

namespace LaravelApiFoundation\Actions;

final readonly class ProcessApiRequestAction
{
    public function __construct(
        private ApiRepositoryInterface $repository,
        private MetricsCollector $metrics,
        private LoggerInterface $logger
    ) {}

    public function execute(ApiRequestDTO $request): ApiResponseDTO
    {
        $this->logger->info('Processing API request', ['request' => $request]);
        
        $response = $this->repository->create($request);
        
        $this->metrics->recordApiCall(
            provider: $request->provider,
            duration: $response->duration,
            status: $response->statusCode
        );

        return $response;
    }
}
```

#### 2. Orchestration Services
```php
declare(strict_types=1);

namespace LaravelApiFoundation\Services;

final readonly class ApiOrchestrationService
{
    public function __construct(
        private ProcessApiRequestAction $processRequest,
        private ValidateApiRequestAction $validateRequest,
        private EventDispatcherInterface $dispatcher,
        private LoggerInterface $logger
    ) {}

    public function process(ApiRequestDTO $request): ApiResponseDTO
    {
        $this->validateRequest->execute($request);
        
        $response = $this->processRequest->execute($request);
        
        $this->dispatcher->dispatch(new ApiRequestProcessed($request, $response));
        
        return $response;
    }
}
```

### Infrastructure Components

#### 1. Rate Limiting
```php
declare(strict_types=1);

namespace LaravelApiFoundation\Http\Middleware;

final class RateLimitMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
```

#### 2. Caching Strategy
```php
declare(strict_types=1);

namespace LaravelApiFoundation\Cache;

final readonly class ApiResponseCache
{
    public function __construct(
        private CacheManager $cache,
        private LoggerInterface $logger
    ) {}

    public function remember(string $key, ApiRequestDTO $request): ApiResponseDTO
    {
        return $this->cache->remember(
            $key,
            config('api-foundation.cache.ttl'),
            fn() => $this->processRequest($request)
        );
    }
}
```

### Monitoring & Metrics

#### 1. Metrics Collection
```php
declare(strict_types=1);

namespace LaravelApiFoundation\Metrics;

final readonly class MetricsCollector
{
    public function __construct(
        private PrometheusAdapter $prometheus,
        private LoggerInterface $logger
    ) {}

    public function recordApiCall(
        string $provider,
        float $duration,
        int $statusCode
    ): void {
        $this->prometheus->histogram(
            name: 'api_request_duration_seconds',
            help: 'API request duration in seconds',
            labels: ['provider' => $provider, 'status' => $statusCode]
        )->observe($duration);
    }
}
```

### Directory Structure
```
src/
├── actions/
│   ├── process-api-request.php
│   └── validate-api-request.php
├── cache/
│   └── api-response-cache.php
├── contracts/
│   ├── provider-interface.php
│   └── repository-interface.php
├── dtos/
│   ├── api-request-dto.php
│   └── api-response-dto.php
├── exceptions/
│   ├── api-exception.php
│   └── validation-exception.php
├── factories/
│   └── provider-factory.php
├── http/
│   ├── controllers/
│   ├── middleware/
│   └── requests/
├── managers/
│   └── api-manager.php
├── metrics/
│   └── metrics-collector.php
├── models/
│   └── api-log.php
├── repositories/
│   └── api-repository.php
├── services/
│   └── api-orchestration-service.php
├── strategies/
│   ├── graphql-strategy.php
│   └── rest-strategy.php
└── value-objects/
    └── api-endpoint.php
```

### Configuration
```php
// config/api-foundation.php
return [
    'providers' => [
        'default' => 'rest',
        'supported' => [
            'rest' => RestApiProvider::class,
            'graphql' => GraphQLProvider::class,
        ],
    ],
    'cache' => [
        'enabled' => true,
        'ttl' => 3600,
        'prefix' => 'api_foundation',
    ],
    'rate_limiting' => [
        'enabled' => true,
        'max_attempts' => 60,
        'decay_minutes' => 1,
    ],
    'monitoring' => [
        'prometheus' => [
            'enabled' => true,
            'push_gateway' => env('PROMETHEUS_GATEWAY'),
        ],
        'sentry' => [
            'enabled' => true,
            'dsn' => env('SENTRY_DSN'),
        ],
    ],
];
```

This architecture follows all specified principles:
- Strict typing
- Final classes by default
- Readonly properties
- Constructor property promotion
- Interface segregation
- Proper DTOs
- Value Objects
- Repository pattern
- Single Action pattern
- Service Orchestration
- Comprehensive logging
- Metrics collection
- Error handling
- Rate limiting
- Caching strategies

The implementation provides a solid foundation for building scalable, maintainable, and performant Laravel APIs.
