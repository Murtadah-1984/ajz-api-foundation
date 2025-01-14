<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Settings
    |--------------------------------------------------------------------------
    |
    | Configure authentication-related settings for the auth domain.
    |
    */
    'auth' => [
        // Token lifetime in minutes
        'token_lifetime' => env('AUTH_TOKEN_LIFETIME', 60),
        
        // Refresh token lifetime in minutes
        'refresh_token_lifetime' => env('AUTH_REFRESH_TOKEN_LIFETIME', 1440),
        
        // Maximum login attempts before lockout
        'max_attempts' => env('AUTH_MAX_ATTEMPTS', 5),
        
        // Lockout duration in minutes
        'lockout_duration' => env('AUTH_LOCKOUT_DURATION', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | OAuth Settings
    |--------------------------------------------------------------------------
    |
    | Configure OAuth providers and their settings.
    |
    */
    'oauth' => [
        'providers' => [
            'google' => [
                'enabled' => env('OAUTH_GOOGLE_ENABLED', false),
                'client_id' => env('OAUTH_GOOGLE_CLIENT_ID'),
                'client_secret' => env('OAUTH_GOOGLE_CLIENT_SECRET'),
                'redirect' => env('OAUTH_GOOGLE_REDIRECT'),
            ],
            'facebook' => [
                'enabled' => env('OAUTH_FACEBOOK_ENABLED', false),
                'client_id' => env('OAUTH_FACEBOOK_CLIENT_ID'),
                'client_secret' => env('OAUTH_FACEBOOK_CLIENT_SECRET'),
                'redirect' => env('OAUTH_FACEBOOK_REDIRECT'),
            ],
            'github' => [
                'enabled' => env('OAUTH_GITHUB_ENABLED', false),
                'client_id' => env('OAUTH_GITHUB_CLIENT_ID'),
                'client_secret' => env('OAUTH_GITHUB_CLIENT_SECRET'),
                'redirect' => env('OAUTH_GITHUB_REDIRECT'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Role & Permission Settings
    |--------------------------------------------------------------------------
    |
    | Configure role and permission related settings.
    |
    */
    'rbac' => [
        // Enable role caching
        'cache_enabled' => env('AUTH_RBAC_CACHE_ENABLED', true),
        
        // Cache duration in minutes
        'cache_duration' => env('AUTH_RBAC_CACHE_DURATION', 60),
        
        // Default roles to create
        'default_roles' => [
            'admin' => 'System Administrator',
            'user' => 'Regular User',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | Configure security-related settings.
    |
    */
    'security' => [
        // Enforce HTTPS
        'force_https' => env('AUTH_FORCE_HTTPS', true),
        
        // Enable rate limiting
        'rate_limiting' => env('AUTH_RATE_LIMITING', true),
        
        // Rate limit attempts per minute
        'rate_limit_attempts' => env('AUTH_RATE_LIMIT_ATTEMPTS', 60),
        
        // Enable IP blocking
        'ip_blocking' => env('AUTH_IP_BLOCKING', true),
        
        // Maximum failed attempts before IP block
        'max_failed_attempts' => env('AUTH_MAX_FAILED_ATTEMPTS', 100),
        
        // IP block duration in minutes
        'ip_block_duration' => env('AUTH_IP_BLOCK_DURATION', 60),
    ],
];
