<?php

return [
    // Authentication
    'login' => [
        'success' => 'Вход выполнен успешно',
        'failed' => 'Неверные учетные данные',
        'throttle' => 'Слишком много попыток входа. Пожалуйста, попробуйте снова через :seconds секунд.',
        'not_verified' => 'Ваша учетная запись не подтверждена.',
        'already_logged_in' => 'Вы уже вошли в систему.',
    ],
    'logout' => [
        'success' => 'Выход выполнен успешно',
    ],
    'register' => [
        'success' => 'Регистрация выполнена успешно',
        'failed' => 'Ошибка при регистрации',
        'email_taken' => 'Этот email уже используется',
    ],
    'password' => [
        'reset' => [
            'success' => 'Пароль успешно сброшен',
            'failed' => 'Не удалось сбросить пароль',
            'token_invalid' => 'Недействительный токен сброса пароля',
        ],
        'requirements' => [
            'length' => 'Пароль должен содержать не менее :length символов',
            'special' => 'Пароль должен содержать как минимум один специальный символ',
            'number' => 'Пароль должен содержать как минимум одну цифру',
            'mixed_case' => 'Пароль должен содержать как заглавные, так и строчные буквы',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => 'Двухфакторная аутентификация включена',
        'disabled' => 'Двухфакторная аутентификация отключена',
        'verify' => 'Пожалуйста, подтвердите ваш код двухфакторной аутентификации',
        'invalid' => 'Недействительный код двухфакторной аутентификации',
    ],

    // OAuth
    'oauth' => [
        'success' => 'Успешная аутентификация через :provider',
        'failed' => 'Ошибка аутентификации через :provider',
        'email_required' => 'Для аутентификации требуется доступ к email',
        'invalid_state' => 'Недействительное состояние OAuth',
        'already_linked' => 'Аккаунт уже связан с :provider',
    ],

    // Permissions
    'permissions' => [
        'created' => 'Разрешение успешно создано',
        'updated' => 'Разрешение успешно обновлено',
        'deleted' => 'Разрешение успешно удалено',
        'assigned' => 'Разрешение успешно назначено',
        'revoked' => 'Разрешение успешно отозвано',
    ],

    // Roles
    'roles' => [
        'created' => 'Роль успешно создана',
        'updated' => 'Роль успешно обновлена',
        'deleted' => 'Роль успешно удалена',
        'assigned' => 'Роль успешно назначена',
        'revoked' => 'Роль успешно отозвана',
    ],

    // Users
    'users' => [
        'created' => 'Пользователь успешно создан',
        'updated' => 'Пользователь успешно обновлен',
        'deleted' => 'Пользователь успешно удален',
    ],

    // Security
    'security' => [
        'ip_blocked' => 'Ваш IP-адрес заблокирован из-за слишком большого количества неудачных попыток',
        'session_expired' => 'Ваша сессия истекла',
        'invalid_token' => 'Недействительный или просроченный токен',
        'csrf_token_mismatch' => 'Недействительный CSRF-токен',
    ],
];
