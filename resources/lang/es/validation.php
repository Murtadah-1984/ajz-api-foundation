<?php

return [
    'email' => [
        'required' => 'El correo electrónico es obligatorio',
        'email' => 'Por favor, introduzca un correo electrónico válido',
        'unique' => 'Este correo electrónico ya está en uso',
        'exists' => 'No se encontró ninguna cuenta con este correo electrónico',
    ],
    'password' => [
        'required' => 'La contraseña es obligatoria',
        'min' => 'La contraseña debe tener al menos :min caracteres',
        'confirmed' => 'La confirmación de la contraseña no coincide',
        'current' => 'La contraseña actual es incorrecta',
    ],
    'name' => [
        'required' => 'El nombre es obligatorio',
        'min' => 'El nombre debe tener al menos :min caracteres',
        'max' => 'El nombre no puede tener más de :max caracteres',
    ],
    'role' => [
        'required' => 'El rol es obligatorio',
        'exists' => 'El rol seleccionado no existe',
        'invalid' => 'Rol seleccionado inválido',
    ],
    'permission' => [
        'required' => 'El permiso es obligatorio',
        'exists' => 'El permiso seleccionado no existe',
        'invalid' => 'Permiso seleccionado inválido',
    ],
    'token' => [
        'required' => 'El token es obligatorio',
        'invalid' => 'Token proporcionado inválido',
    ],
    '2fa_code' => [
        'required' => 'El código de autenticación de dos factores es obligatorio',
        'digits' => 'El código de autenticación de dos factores debe tener :digits dígitos',
        'invalid' => 'Código de autenticación de dos factores inválido',
    ],
    'provider' => [
        'required' => 'El proveedor OAuth es obligatorio',
        'supported' => 'El proveedor OAuth seleccionado no está soportado',
    ],
    'phone' => [
        'required' => 'El número de teléfono es obligatorio',
        'unique' => 'Este número de teléfono ya está registrado',
        'format' => 'Formato de número de teléfono inválido',
    ],
    'username' => [
        'required' => 'El nombre de usuario es obligatorio',
        'unique' => 'Este nombre de usuario ya está en uso',
        'alpha_dash' => 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos',
        'min' => 'El nombre de usuario debe tener al menos :min caracteres',
        'max' => 'El nombre de usuario no puede tener más de :max caracteres',
    ],
];
