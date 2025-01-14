<?php

return [
    'email' => [
        'required' => 'O e-mail é obrigatório',
        'email' => 'Por favor, insira um e-mail válido',
        'unique' => 'Este e-mail já está em uso',
        'exists' => 'Nenhuma conta encontrada com este e-mail',
    ],
    'password' => [
        'required' => 'A senha é obrigatória',
        'min' => 'A senha deve ter pelo menos :min caracteres',
        'confirmed' => 'A confirmação de senha não corresponde',
        'current' => 'A senha atual está incorreta',
    ],
    'name' => [
        'required' => 'O nome é obrigatório',
        'min' => 'O nome deve ter pelo menos :min caracteres',
        'max' => 'O nome não pode ter mais que :max caracteres',
    ],
    'role' => [
        'required' => 'A função é obrigatória',
        'exists' => 'A função selecionada não existe',
        'invalid' => 'Função selecionada inválida',
    ],
    'permission' => [
        'required' => 'A permissão é obrigatória',
        'exists' => 'A permissão selecionada não existe',
        'invalid' => 'Permissão selecionada inválida',
    ],
    'token' => [
        'required' => 'O token é obrigatório',
        'invalid' => 'Token fornecido inválido',
    ],
    '2fa_code' => [
        'required' => 'O código de autenticação de dois fatores é obrigatório',
        'digits' => 'O código de autenticação de dois fatores deve ter :digits dígitos',
        'invalid' => 'Código de autenticação de dois fatores inválido',
    ],
    'provider' => [
        'required' => 'O provedor OAuth é obrigatório',
        'supported' => 'O provedor OAuth selecionado não é suportado',
    ],
    'phone' => [
        'required' => 'O número de telefone é obrigatório',
        'unique' => 'Este número de telefone já está registrado',
        'format' => 'Formato de número de telefone inválido',
    ],
    'username' => [
        'required' => 'O nome de usuário é obrigatório',
        'unique' => 'Este nome de usuário já está em uso',
        'alpha_dash' => 'O nome de usuário pode conter apenas letras, números, traços e sublinhados',
        'min' => 'O nome de usuário deve ter pelo menos :min caracteres',
        'max' => 'O nome de usuário não pode ter mais que :max caracteres',
    ],
];
