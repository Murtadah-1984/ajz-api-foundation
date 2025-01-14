<?php

return [
    // Authentication
    'login' => [
        'success' => 'Login realizado com sucesso',
        'failed' => 'Credenciais inválidas',
        'throttle' => 'Muitas tentativas de login. Por favor, tente novamente em :seconds segundos.',
        'not_verified' => 'Sua conta não está verificada.',
        'already_logged_in' => 'Você já está logado.',
    ],
    'logout' => [
        'success' => 'Logout realizado com sucesso',
    ],
    'register' => [
        'success' => 'Registro realizado com sucesso',
        'failed' => 'Falha no registro',
        'email_taken' => 'Este e-mail já está em uso',
    ],
    'password' => [
        'reset' => [
            'success' => 'Senha redefinida com sucesso',
            'failed' => 'Não foi possível redefinir a senha',
            'token_invalid' => 'Token de redefinição de senha inválido',
        ],
        'requirements' => [
            'length' => 'A senha deve ter pelo menos :length caracteres',
            'special' => 'A senha deve conter pelo menos um caractere especial',
            'number' => 'A senha deve conter pelo menos um número',
            'mixed_case' => 'A senha deve conter letras maiúsculas e minúsculas',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => 'Autenticação de dois fatores ativada',
        'disabled' => 'Autenticação de dois fatores desativada',
        'verify' => 'Por favor, verifique seu código de autenticação de dois fatores',
        'invalid' => 'Código de autenticação de dois fatores inválido',
    ],

    // OAuth
    'oauth' => [
        'success' => 'Autenticação bem-sucedida com :provider',
        'failed' => 'Falha na autenticação com :provider',
        'email_required' => 'Acesso ao e-mail é necessário para autenticação',
        'invalid_state' => 'Estado OAuth inválido',
        'already_linked' => 'Conta já vinculada ao :provider',
    ],

    // Permissions
    'permissions' => [
        'created' => 'Permissão criada com sucesso',
        'updated' => 'Permissão atualizada com sucesso',
        'deleted' => 'Permissão excluída com sucesso',
        'assigned' => 'Permissão atribuída com sucesso',
        'revoked' => 'Permissão revogada com sucesso',
    ],

    // Roles
    'roles' => [
        'created' => 'Função criada com sucesso',
        'updated' => 'Função atualizada com sucesso',
        'deleted' => 'Função excluída com sucesso',
        'assigned' => 'Função atribuída com sucesso',
        'revoked' => 'Função revogada com sucesso',
    ],

    // Users
    'users' => [
        'created' => 'Usuário criado com sucesso',
        'updated' => 'Usuário atualizado com sucesso',
        'deleted' => 'Usuário excluído com sucesso',
    ],

    // Security
    'security' => [
        'ip_blocked' => 'Seu endereço IP foi bloqueado devido a muitas tentativas falhas',
        'session_expired' => 'Sua sessão expirou',
        'invalid_token' => 'Token inválido ou expirado',
        'csrf_token_mismatch' => 'Token CSRF inválido',
    ],
];
