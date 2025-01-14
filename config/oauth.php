<?php

return [
    'providers' => [
        'github' => [
            'enabled' => env('OAUTH_GITHUB_ENABLED', true),
            'client_id' => env('OAUTH_GITHUB_CLIENT_ID'),
            'client_secret' => env('OAUTH_GITHUB_CLIENT_SECRET'),
            'redirect' => env('OAUTH_GITHUB_REDIRECT_URI'),
            'scopes' => ['read:user', 'user:email'],
        ],
        'google' => [
            'enabled' => env('OAUTH_GOOGLE_ENABLED', true),
            'client_id' => env('OAUTH_GOOGLE_CLIENT_ID'),
            'client_secret' => env('OAUTH_GOOGLE_CLIENT_SECRET'),
            'redirect' => env('OAUTH_GOOGLE_REDIRECT_URI'),
            'scopes' => ['openid', 'profile', 'email', 'https://www.googleapis.com/auth/gmail.readonly'],
        ],
        'apple' => [
            'enabled' => env('OAUTH_APPLE_ENABLED', true),
            'client_id' => env('OAUTH_APPLE_CLIENT_ID'),
            'client_secret' => env('OAUTH_APPLE_CLIENT_SECRET'),
            'redirect' => env('OAUTH_APPLE_REDIRECT_URI'),
            'scopes' => ['name', 'email'],
        ],
        'facebook' => [
            'enabled' => env('OAUTH_FACEBOOK_ENABLED', true),
            'client_id' => env('OAUTH_FACEBOOK_CLIENT_ID'),
            'client_secret' => env('OAUTH_FACEBOOK_CLIENT_SECRET'),
            'redirect' => env('OAUTH_FACEBOOK_REDIRECT_URI'),
            'scopes' => ['email', 'public_profile'],
        ],
        'instagram' => [
            'enabled' => env('OAUTH_INSTAGRAM_ENABLED', true),
            'client_id' => env('OAUTH_INSTAGRAM_CLIENT_ID'),
            'client_secret' => env('OAUTH_INSTAGRAM_CLIENT_SECRET'),
            'redirect' => env('OAUTH_INSTAGRAM_REDIRECT_URI'),
            'scopes' => ['basic', 'public_content'],
        ],
    ],
];
