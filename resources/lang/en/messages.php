<?php

return [
    // Authentication
    'login' => [
        'success' => 'Successfully logged in',
        'failed' => 'Invalid credentials',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
        'not_verified' => 'Your account is not verified.',
        'already_logged_in' => 'You are already logged in.',
    ],
    'logout' => [
        'success' => 'Successfully logged out',
    ],
    'register' => [
        'success' => 'Registration successful',
        'failed' => 'Registration failed',
        'email_taken' => 'Email is already taken',
    ],
    'password' => [
        'reset' => [
            'success' => 'Password has been reset',
            'failed' => 'Unable to reset password',
            'token_invalid' => 'Password reset token is invalid',
        ],
        'requirements' => [
            'length' => 'Password must be at least :length characters',
            'special' => 'Password must contain at least one special character',
            'number' => 'Password must contain at least one number',
            'mixed_case' => 'Password must contain both uppercase and lowercase letters',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => 'Two factor authentication enabled',
        'disabled' => 'Two factor authentication disabled',
        'verify' => 'Please verify your two factor authentication code',
        'invalid' => 'Invalid two factor authentication code',
    ],

    // OAuth
    'oauth' => [
        'success' => 'Successfully authenticated with :provider',
        'failed' => 'Failed to authenticate with :provider',
        'email_required' => 'Email access is required for authentication',
        'invalid_state' => 'Invalid OAuth state',
        'already_linked' => 'Account already linked to :provider',
    ],

    // Permissions
    'permissions' => [
        'created' => 'Permission created successfully',
        'updated' => 'Permission updated successfully',
        'deleted' => 'Permission deleted successfully',
        'assigned' => 'Permission assigned successfully',
        'revoked' => 'Permission revoked successfully',
    ],

    // Roles
    'roles' => [
        'created' => 'Role created successfully',
        'updated' => 'Role updated successfully',
        'deleted' => 'Role deleted successfully',
        'assigned' => 'Role assigned successfully',
        'revoked' => 'Role revoked successfully',
    ],

    // Users
    'users' => [
        'created' => 'User created successfully',
        'updated' => 'User updated successfully',
        'deleted' => 'User deleted successfully',
    ],

    // Security
    'security' => [
        'ip_blocked' => 'Your IP has been blocked due to too many failed attempts',
        'session_expired' => 'Your session has expired',
        'invalid_token' => 'Invalid or expired token',
        'csrf_token_mismatch' => 'Invalid CSRF token',
    ],
];
