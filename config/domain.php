<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | OAuth Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for various OAuth providers including client IDs and secrets.
    | These values are loaded from the domain-specific environment file.
    |
    */
    'oauth' => [
        'google' => [
            'client_id' => env('AUTH_OAUTH_GOOGLE_CLIENT_ID'),
            'client_secret' => env('AUTH_OAUTH_GOOGLE_CLIENT_SECRET'),
            'redirect' => env('AUTH_OAUTH_GOOGLE_REDIRECT_URI'),
        ],
        'facebook' => [
            'client_id' => env('AUTH_OAUTH_FACEBOOK_CLIENT_ID'),
            'client_secret' => env('AUTH_OAUTH_FACEBOOK_CLIENT_SECRET'),
            'redirect' => env('AUTH_OAUTH_FACEBOOK_REDIRECT_URI'),
        ],
        'github' => [
            'client_id' => env('AUTH_OAUTH_GITHUB_CLIENT_ID'),
            'client_secret' => env('AUTH_OAUTH_GITHUB_CLIENT_SECRET'),
            'redirect' => env('AUTH_OAUTH_GITHUB_REDIRECT_URI'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | Various security-related configurations including 2FA, session management,
    | and IP blocking settings.
    |
    */
    'security' => [
        '2fa' => [
            'enabled' => env('AUTH_2FA_ENABLED', true),
            'issuer' => env('AUTH_2FA_ISSUER', 'MyDDD API'),
            'digits' => env('AUTH_2FA_DIGITS', 6),
            'window' => env('AUTH_2FA_WINDOW', 1),
        ],
        'session' => [
            'lifetime' => env('AUTH_SESSION_LIFETIME', 120),
            'single_session' => env('AUTH_SINGLE_SESSION_ENABLED', true),
        ],
        'ip_blocking' => [
            'enabled' => env('AUTH_IP_BLOCKING_ENABLED', true),
            'max_attempts' => env('AUTH_IP_MAX_ATTEMPTS', 5),
            'decay_minutes' => env('AUTH_IP_DECAY_MINUTES', 15),
        ],
        'password_policy' => [
            'min_length' => env('AUTH_PASSWORD_MIN_LENGTH', 8),
            'require_special_char' => env('AUTH_PASSWORD_REQUIRE_SPECIAL', true),
            'require_number' => env('AUTH_PASSWORD_REQUIRE_NUMBER', true),
            'require_mixed_case' => env('AUTH_PASSWORD_REQUIRE_MIXED_CASE', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for API-related features including rate limiting and
    | token management.
    |
    */
    'api' => [
        'rate_limiting' => [
            'enabled' => env('AUTH_RATE_LIMITING_ENABLED', true),
            'max_attempts' => env('AUTH_RATE_LIMIT_MAX_ATTEMPTS', 60),
            'decay_minutes' => env('AUTH_RATE_LIMIT_DECAY_MINUTES', 1),
        ],
        'tokens' => [
            'expiration' => env('AUTH_TOKEN_EXPIRATION', 60), // minutes
            'refresh_ttl' => env('AUTH_REFRESH_TOKEN_TTL', 20160), // minutes (14 days)
            'rotation_enabled' => env('AUTH_TOKEN_ROTATION_ENABLED', true),
            'rotation_interval' => env('AUTH_TOKEN_ROTATION_INTERVAL', 1440), // minutes (24 hours)
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring and Logging
    |--------------------------------------------------------------------------
    |
    | Settings for audit trails, activity logging, and monitoring features.
    |
    */
    'monitoring' => [
        'audit_trail' => [
            'enabled' => env('AUTH_AUDIT_ENABLED', true),
            'retention_days' => env('AUTH_AUDIT_RETENTION_DAYS', 90),
        ],
        'activity_log' => [
            'enabled' => env('AUTH_ACTIVITY_LOG_ENABLED', true),
            'retention_days' => env('AUTH_ACTIVITY_LOG_RETENTION_DAYS', 30),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Cache settings for various auth-related data.
    |
    */
    'cache' => [
        'driver' => env('AUTH_CACHE_DRIVER', 'redis'),
        'prefix' => env('AUTH_CACHE_PREFIX', 'auth'),
        'ttl' => [
            'permissions' => env('AUTH_CACHE_PERMISSIONS_TTL', 3600),
            'roles' => env('AUTH_CACHE_ROLES_TTL', 3600),
            'user_permissions' => env('AUTH_CACHE_USER_PERMISSIONS_TTL', 3600),
        ],
    ],
];
