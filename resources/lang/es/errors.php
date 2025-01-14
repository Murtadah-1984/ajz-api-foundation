<?php

return [
    'unauthorized' => 'Acceso no autorizado',
    'forbidden' => 'Acceso prohibido',
    'not_found' => 'Recurso no encontrado',
    'validation_failed' => 'Validación fallida',
    'server_error' => 'Error interno del servidor',
    
    'auth' => [
        'invalid_credentials' => 'Credenciales inválidas',
        'token_expired' => 'El token de autenticación ha expirado',
        'token_invalid' => 'Token de autenticación inválido',
        'user_not_found' => 'Usuario no encontrado',
        'not_verified' => 'Correo electrónico no verificado',
        'already_verified' => 'Correo electrónico ya verificado',
    ],

    'oauth' => [
        'provider_not_found' => 'Proveedor OAuth no encontrado',
        'invalid_state' => 'Estado OAuth inválido',
        'callback_error' => 'Error durante la llamada OAuth',
        'user_denied' => 'Acceso denegado por el usuario',
    ],

    'permissions' => [
        'not_found' => 'Permiso no encontrado',
        'already_exists' => 'El permiso ya existe',
        'cannot_delete' => 'No se puede eliminar este permiso',
        'invalid_format' => 'Formato de permiso inválido',
    ],

    'roles' => [
        'not_found' => 'Rol no encontrado',
        'already_exists' => 'El rol ya existe',
        'cannot_delete' => 'No se puede eliminar este rol',
        'invalid_format' => 'Formato de rol inválido',
    ],

    'users' => [
        'not_found' => 'Usuario no encontrado',
        'already_exists' => 'El usuario ya existe',
        'cannot_delete' => 'No se puede eliminar este usuario',
        'invalid_format' => 'Formato de usuario inválido',
    ],

    'security' => [
        'ip_blocked' => 'Dirección IP bloqueada',
        'too_many_attempts' => 'Demasiados intentos',
        'invalid_2fa' => 'Código de autenticación de dos factores inválido',
        'session_expired' => 'La sesión ha expirado',
    ],
];
