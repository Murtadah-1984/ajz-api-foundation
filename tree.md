├───config
│   ├───api-base
│   ├───cache-management
│   ├───moniter
│   └───rate-limit
├───resources
│   ├───docker
│   └───swagger
├───routes
├───src
│   ├───Actions
│   ├───Commands
│   ├───database
│   │   ├───migrations
|   |   |   ├───CreateApiMetricsTable 
|   |   |   └───CreateRateLimitTiersTable 
│   │   └───seeders
|   │       └───RateLimitTiersSeeder 
│   ├───Http
│   │   └───Middleware
|   |       └───EnhancedApiRateLimit
│   ├───Providers
│   ├───Services
│   │   └───Base
|   |       ├───EnhancedCacheService
|   |       ├───PerformanceMonitoringService
|   |       ├───RateLimitService
|   |       └───SecurityService    
│   ├───Traits
│   └───ValueObjects
│       ├───Contact
│       ├───Identity
│       ├───Location
│       └───Person
├───tests
└───vendor
   └───
