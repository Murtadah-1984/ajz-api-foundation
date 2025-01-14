<?php

return [
    // Authentication
    'login' => [
        'success' => 'Inicio de sesión exitoso',
        'failed' => 'Credenciales inválidas',
        'throttle' => 'Demasiados intentos de inicio de sesión. Por favor, inténtelo de nuevo en :seconds segundos.',
        'not_verified' => 'Su cuenta no está verificada.',
        'already_logged_in' => 'Ya ha iniciado sesión.',
    ],
    'logout' => [
        'success' => 'Sesión cerrada exitosamente',
    ],
    'register' => [
        'success' => 'Registro exitoso',
        'failed' => 'Error en el registro',
        'email_taken' => 'El correo electrónico ya está en uso',
    ],
    'password' => [
        'reset' => [
            'success' => 'La contraseña ha sido restablecida',
            'failed' => 'No se pudo restablecer la contraseña',
            'token_invalid' => 'El token de restablecimiento de contraseña es inválido',
        ],
        'requirements' => [
            'length' => 'La contraseña debe tener al menos :length caracteres',
            'special' => 'La contraseña debe contener al menos un carácter especial',
            'number' => 'La contraseña debe contener al menos un número',
            'mixed_case' => 'La contraseña debe contener mayúsculas y minúsculas',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => 'Autenticación de dos factores habilitada',
        'disabled' => 'Autenticación de dos factores deshabilitada',
        'verify' => 'Por favor, verifique su código de autenticación de dos factores',
        'invalid' => 'Código de autenticación de dos factores inválido',
    ],

    // OAuth
    'oauth' => [
        'success' => 'Autenticación exitosa con :provider',
        'failed' => 'Error al autenticar con :provider',
        'email_required' => 'Se requiere acceso al correo electrónico para la autenticación',
        'invalid_state' => 'Estado OAuth inválido',
        'already_linked' => 'La cuenta ya está vinculada con :provider',
    ],

    // Permissions
    'permissions' => [
        'created' => 'Permiso creado exitosamente',
        'updated' => 'Permiso actualizado exitosamente',
        'deleted' => 'Permiso eliminado exitosamente',
        'assigned' => 'Permiso asignado exitosamente',
        'revoked' => 'Permiso revocado exitosamente',
    ],

    // Roles
    'roles' => [
        'created' => 'Rol creado exitosamente',
        'updated' => 'Rol actualizado exitosamente',
        'deleted' => 'Rol eliminado exitosamente',
        'assigned' => 'Rol asignado exitosamente',
        'revoked' => 'Rol revocado exitosamente',
    ],

    // Users
    'users' => [
        'created' => 'Usuario creado exitosamente',
        'updated' => 'Usuario actualizado exitosamente',
        'deleted' => 'Usuario eliminado exitosamente',
    ],

    // Security
    'security' => [
        'ip_blocked' => 'Su dirección IP ha sido bloqueada debido a demasiados intentos fallidos',
        'session_expired' => 'Su sesión ha expirado',
        'invalid_token' => 'Token inválido o expirado',
        'csrf_token_mismatch' => 'Token CSRF inválido',
    ],
];
