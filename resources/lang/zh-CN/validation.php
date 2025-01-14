<?php

return [
    'email' => [
        'required' => '邮箱地址为必填项',
        'email' => '请输入有效的邮箱地址',
        'unique' => '该邮箱地址已被使用',
        'exists' => '未找到使用此邮箱的账户',
    ],
    'password' => [
        'required' => '密码为必填项',
        'min' => '密码必须至少包含 :min 个字符',
        'confirmed' => '密码确认不匹配',
        'current' => '当前密码不正确',
    ],
    'name' => [
        'required' => '名称为必填项',
        'min' => '名称必须至少包含 :min 个字符',
        'max' => '名称不能超过 :max 个字符',
    ],
    'role' => [
        'required' => '角色为必填项',
        'exists' => '所选角色不存在',
        'invalid' => '所选角色无效',
    ],
    'permission' => [
        'required' => '权限为必填项',
        'exists' => '所选权限不存在',
        'invalid' => '所选权限无效',
    ],
    'token' => [
        'required' => '令牌为必填项',
        'invalid' => '提供的令牌无效',
    ],
    '2fa_code' => [
        'required' => '双重认证码为必填项',
        'digits' => '双重认证码必须是 :digits 位数字',
        'invalid' => '无效的双重认证码',
    ],
    'provider' => [
        'required' => 'OAuth 提供商为必填项',
        'supported' => '所选 OAuth 提供商不受支持',
    ],
    'phone' => [
        'required' => '电话号码为必填项',
        'unique' => '该电话号码已被注册',
        'format' => '无效的电话号码格式',
    ],
    'username' => [
        'required' => '用户名为必填项',
        'unique' => '该用户名已被使用',
        'alpha_dash' => '用户名只能包含字母、数字、破折号和下划线',
        'min' => '用户名必须至少包含 :min 个字符',
        'max' => '用户名不能超过 :max 个字符',
    ],
];
