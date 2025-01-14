<?php

return [
    'email' => [
        'required' => 'Email address is required',
        'email' => 'Please enter a valid email address',
        'unique' => 'This email address is already taken',
        'exists' => 'No account found with this email address',
    ],
    'password' => [
        'required' => 'Password is required',
        'min' => 'Password must be at least :min characters',
        'confirmed' => 'Password confirmation does not match',
        'current' => 'Current password is incorrect',
    ],
    'name' => [
        'required' => 'Name is required',
        'min' => 'Name must be at least :min characters',
        'max' => 'Name cannot exceed :max characters',
    ],
    'role' => [
        'required' => 'Role is required',
        'exists' => 'Selected role does not exist',
        'invalid' => 'Invalid role selected',
    ],
    'permission' => [
        'required' => 'Permission is required',
        'exists' => 'Selected permission does not exist',
        'invalid' => 'Invalid permission selected',
    ],
    'token' => [
        'required' => 'Token is required',
        'invalid' => 'Invalid token provided',
    ],
    '2fa_code' => [
        'required' => 'Two-factor authentication code is required',
        'digits' => 'Two-factor authentication code must be :digits digits',
        'invalid' => 'Invalid two-factor authentication code',
    ],
    'provider' => [
        'required' => 'OAuth provider is required',
        'supported' => 'Selected OAuth provider is not supported',
    ],
    'phone' => [
        'required' => 'Phone number is required',
        'unique' => 'This phone number is already registered',
        'format' => 'Invalid phone number format',
    ],
    'username' => [
        'required' => 'Username is required',
        'unique' => 'This username is already taken',
        'alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores',
        'min' => 'Username must be at least :min characters',
        'max' => 'Username cannot exceed :max characters',
    ],
];
