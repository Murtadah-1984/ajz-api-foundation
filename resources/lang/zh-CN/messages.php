<?php

return [
    // Authentication
    'login' => [
        'success' => '登录成功',
        'failed' => '无效的凭据',
        'throttle' => '登录尝试次数过多，请在 :seconds 秒后重试。',
        'not_verified' => '您的账户尚未验证。',
        'already_logged_in' => '您已经登录。',
    ],
    'logout' => [
        'success' => '退出登录成功',
    ],
    'register' => [
        'success' => '注册成功',
        'failed' => '注册失败',
        'email_taken' => '该邮箱已被使用',
    ],
    'password' => [
        'reset' => [
            'success' => '密码重置成功',
            'failed' => '无法重置密码',
            'token_invalid' => '无效的密码重置令牌',
        ],
        'requirements' => [
            'length' => '密码必须至少包含 :length 个字符',
            'special' => '密码必须包含至少一个特殊字符',
            'number' => '密码必须包含至少一个数字',
            'mixed_case' => '密码必须包含大小写字母',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => '双重认证已启用',
        'disabled' => '双重认证已禁用',
        'verify' => '请验证您的双重认证码',
        'invalid' => '无效的双重认证码',
    ],

    // OAuth
    'oauth' => [
        'success' => '通过 :provider 认证成功',
        'failed' => '通过 :provider 认证失败',
        'email_required' => '认证需要邮箱访问权限',
        'invalid_state' => '无效的 OAuth 状态',
        'already_linked' => '账户已与 :provider 关联',
    ],

    // Permissions
    'permissions' => [
        'created' => '权限创建成功',
        'updated' => '权限更新成功',
        'deleted' => '权限删除成功',
        'assigned' => '权限分配成功',
        'revoked' => '权限撤销成功',
    ],

    // Roles
    'roles' => [
        'created' => '角色创建成功',
        'updated' => '角色更新成功',
        'deleted' => '角色删除成功',
        'assigned' => '角色分配成功',
        'revoked' => '角色撤销成功',
    ],

    // Users
    'users' => [
        'created' => '用户创建成功',
        'updated' => '用户更新成功',
        'deleted' => '用户删除成功',
    ],

    // Security
    'security' => [
        'ip_blocked' => '由于多次失败尝试，您的 IP 地址已被封锁',
        'session_expired' => '您的会话已过期',
        'invalid_token' => '无效或过期的令牌',
        'csrf_token_mismatch' => '无效的 CSRF 令牌',
    ],
];
