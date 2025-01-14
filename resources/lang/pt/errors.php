<?php

return [
    'unauthorized' => 'Acesso não autorizado',
    'forbidden' => 'Acesso proibido',
    'not_found' => 'Recurso não encontrado',
    'validation_failed' => 'Falha na validação',
    'server_error' => 'Erro interno do servidor',
    
    'auth' => [
        'invalid_credentials' => 'Credenciais inválidas',
        'token_expired' => 'Token de autenticação expirado',
        'token_invalid' => 'Token de autenticação inválido',
        'user_not_found' => 'Usuário não encontrado',
        'not_verified' => 'E-mail não verificado',
        'already_verified' => 'E-mail já verificado',
    ],

    'oauth' => [
        'provider_not_found' => 'Provedor OAuth não encontrado',
        'invalid_state' => 'Estado OAuth inválido',
        'callback_error' => 'Erro durante callback OAuth',
        'user_denied' => 'Acesso negado pelo usuário',
    ],

    'permissions' => [
        'not_found' => 'Permissão não encontrada',
        'already_exists' => 'Permissão já existe',
        'cannot_delete' => 'Não é possível excluir esta permissão',
        'invalid_format' => 'Formato de permissão inválido',
    ],

    'roles' => [
        'not_found' => 'Função não encontrada',
        'already_exists' => 'Função já existe',
        'cannot_delete' => 'Não é possível excluir esta função',
        'invalid_format' => 'Formato de função inválido',
    ],

    'users' => [
        'not_found' => 'Usuário não encontrado',
        'already_exists' => 'Usuário já existe',
        'cannot_delete' => 'Não é possível excluir este usuário',
        'invalid_format' => 'Formato de usuário inválido',
    ],

    'security' => [
        'ip_blocked' => 'Endereço IP bloqueado',
        'too_many_attempts' => 'Muitas tentativas',
        'invalid_2fa' => 'Código de autenticação de dois fatores inválido',
        'session_expired' => 'Sessão expirada',
    ],
];
