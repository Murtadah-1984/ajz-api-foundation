<?php

return [
    'unauthorized' => 'Unauthorized access',
    'forbidden' => 'Forbidden access',
    'not_found' => 'Resource not found',
    'validation_failed' => 'Validation failed',
    'server_error' => 'Internal server error',
    
    'auth' => [
        'invalid_credentials' => 'Invalid credentials provided',
        'token_expired' => 'Authentication token has expired',
        'token_invalid' => 'Invalid authentication token',
        'user_not_found' => 'User not found',
        'not_verified' => 'Email not verified',
        'already_verified' => 'Email already verified',
    ],

    'oauth' => [
        'provider_not_found' => 'OAuth provider not found',
        'invalid_state' => 'Invalid OAuth state',
        'callback_error' => 'Error during OAuth callback',
        'user_denied' => 'Access denied by user',
    ],

    'permissions' => [
        'not_found' => 'Permission not found',
        'already_exists' => 'Permission already exists',
        'cannot_delete' => 'Cannot delete this permission',
        'invalid_format' => 'Invalid permission format',
    ],

    'roles' => [
        'not_found' => 'Role not found',
        'already_exists' => 'Role already exists',
        'cannot_delete' => 'Cannot delete this role',
        'invalid_format' => 'Invalid role format',
    ],

    'users' => [
        'not_found' => 'User not found',
        'already_exists' => 'User already exists',
        'cannot_delete' => 'Cannot delete this user',
        'invalid_format' => 'Invalid user format',
    ],

    'security' => [
        'ip_blocked' => 'IP address has been blocked',
        'too_many_attempts' => 'Too many attempts',
        'invalid_2fa' => 'Invalid two-factor authentication code',
        'session_expired' => 'Session has expired',
    ],
];
