<?php

// Configuration: config/cache-management.php
return [
    'cache_version' => env('CACHE_VERSION', 'v1'),
    'volatility_patterns' => [
        'user_' => 3600,
        'product_' => 7200,
        'config_' => 86400,
        'static_' => 604800,
    ],
    'monitoring' => [
        'thresholds' => [
            'cache' => [
                'miss_rate' => 0.3, // Alert if miss rate exceeds 30%
                'hit_latency' => 100, // Alert if cache hit latency exceeds 100ms
            ],
            'queue' => [
                'length' => 1000, // Alert if queue length exceeds 1000
                'processing_time' => 300, // Alert if processing time exceeds 300s
            ],
        ],
        'cleanup' => [
            'frequency' => 'daily',
            'keep_days' => 7,
        ],
    ],
    'rate_limits' => [
        'tiers' => [
            'bronze' => [
                'rate' => 60,
                'burst' => 5,
                'period' => 60,
            ],
            'silver' => [
                'rate' => 300,
                'burst' => 15,
                'period' => 60,
            ],
            'gold' => [
                'rate' => 1000,
                'burst' => 50,
                'period' => 60,
            ],
        ],
    ],
];

