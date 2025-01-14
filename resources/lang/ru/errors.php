<?php

return [
    'unauthorized' => 'Неавторизованный доступ',
    'forbidden' => 'Доступ запрещен',
    'not_found' => 'Ресурс не найден',
    'validation_failed' => 'Ошибка валидации',
    'server_error' => 'Внутренняя ошибка сервера',
    
    'auth' => [
        'invalid_credentials' => 'Неверные учетные данные',
        'token_expired' => 'Срок действия токена аутентификации истек',
        'token_invalid' => 'Недействительный токен аутентификации',
        'user_not_found' => 'Пользователь не найден',
        'not_verified' => 'Email не подтвержден',
        'already_verified' => 'Email уже подтвержден',
    ],

    'oauth' => [
        'provider_not_found' => 'Провайдер OAuth не найден',
        'invalid_state' => 'Недействительное состояние OAuth',
        'callback_error' => 'Ошибка во время обратного вызова OAuth',
        'user_denied' => 'Доступ запрещен пользователем',
    ],

    'permissions' => [
        'not_found' => 'Разрешение не найдено',
        'already_exists' => 'Разрешение уже существует',
        'cannot_delete' => 'Невозможно удалить это разрешение',
        'invalid_format' => 'Недействительный формат разрешения',
    ],

    'roles' => [
        'not_found' => 'Роль не найдена',
        'already_exists' => 'Роль уже существует',
        'cannot_delete' => 'Невозможно удалить эту роль',
        'invalid_format' => 'Недействительный формат роли',
    ],

    'users' => [
        'not_found' => 'Пользователь не найден',
        'already_exists' => 'Пользователь уже существует',
        'cannot_delete' => 'Невозможно удалить этого пользователя',
        'invalid_format' => 'Недействительный формат пользователя',
    ],

    'security' => [
        'ip_blocked' => 'IP-адрес заблокирован',
        'too_many_attempts' => 'Слишком много попыток',
        'invalid_2fa' => 'Недействительный код двухфакторной аутентификации',
        'session_expired' => 'Сессия истекла',
    ],
];
