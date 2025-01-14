<?php

return [
    'unauthorized' => '未经授权的访问',
    'forbidden' => '禁止访问',
    'not_found' => '未找到资源',
    'validation_failed' => '验证失败',
    'server_error' => '服务器内部错误',
    
    'auth' => [
        'invalid_credentials' => '无效的凭据',
        'token_expired' => '认证令牌已过期',
        'token_invalid' => '无效的认证令牌',
        'user_not_found' => '未找到用户',
        'not_verified' => '邮箱未验证',
        'already_verified' => '邮箱已验证',
    ],

    'oauth' => [
        'provider_not_found' => '未找到 OAuth 提供商',
        'invalid_state' => '无效的 OAuth 状态',
        'callback_error' => 'OAuth 回调过程中出错',
        'user_denied' => '用户拒绝访问',
    ],

    'permissions' => [
        'not_found' => '未找到权限',
        'already_exists' => '权限已存在',
        'cannot_delete' => '无法删除此权限',
        'invalid_format' => '无效的权限格式',
    ],

    'roles' => [
        'not_found' => '未找到角色',
        'already_exists' => '角色已存在',
        'cannot_delete' => '无法删除此角色',
        'invalid_format' => '无效的角色格式',
    ],

    'users' => [
        'not_found' => '未找到用户',
        'already_exists' => '用户已存在',
        'cannot_delete' => '无法删除此用户',
        'invalid_format' => '无效的用户格式',
    ],

    'security' => [
        'ip_blocked' => 'IP 地址已被封锁',
        'too_many_attempts' => '尝试次数过多',
        'invalid_2fa' => '无效的双重认证码',
        'session_expired' => '会话已过期',
    ],
];
