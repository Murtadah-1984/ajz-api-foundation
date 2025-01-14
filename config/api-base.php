<?php

return [
    'docker' => [
        'php_version' => '8.3',
        'nginx_version' => '1.25',
        'mysql_version' => '8.0',
        'redis_version' => '7.0'
    ],
    'versioning' => [
        'default_version' => 'v1',
        'header_version' => 'Accept-Version'
    ],
    'rate_limiting' => [
        'enabled' => true,
        'max_attempts' => 60,
        'decay_minutes' => 1
    ],
    'caching' => [
        'default_ttl' => 3600,
        'prefix' => 'api_cache'
    ]
];
