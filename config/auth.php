<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Domain Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your authentication domain settings. These values
    | are used throughout the authentication domain to maintain consistency
    | and provide configuration options that can be easily modified.
    |
    */

    'registration' => [
        // Maximum number of registration attempts per IP per hour
        'throttle' => [
            'max_attempts' => 6,
            'decay_minutes' => 60,
        ],
    ],

    'verification' => [
        'email' => [
            // Number of days before email verification link expires
            'expire_days' => 7,
            
            // Route name for email verification
            'route' => 'auth.verify-email',
        ],
        
        'otp' => [
            // OTP length
            'length' => 6,
            
            // OTP expiry time in minutes
            'expire_minutes' => 10,
            
            // Maximum number of OTP verification attempts
            'max_attempts' => 3,
        ],
    ],

    'passwords' => [
        // Minimum password length
        'min_length' => 8,
        
        // Whether to require mixed case
        'require_mixed_case' => true,
        
        // Whether to require numbers
        'require_numbers' => true,
        
        // Whether to require symbols
        'require_symbols' => false,
        
        // Whether to check for compromised passwords
        'check_compromised' => true,
    ],

    'tokens' => [
        // Access token expiry time in minutes
        'expire_minutes' => 60,
        
        // Whether to allow multiple tokens per user
        'multiple_tokens' => true,
        
        // Maximum number of tokens per user
        'max_tokens' => 5,
    ],

    'session' => [
        // Session lifetime in minutes
        'lifetime' => 120,
        
        // Whether to expire session on browser close
        'expire_on_close' => false,
    ],
];
