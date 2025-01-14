<?php

return [
    'email' => [
        'required' => 'Email обязателен для заполнения',
        'email' => 'Пожалуйста, введите действительный email адрес',
        'unique' => 'Этот email уже используется',
        'exists' => 'Не найден аккаунт с таким email адресом',
    ],
    'password' => [
        'required' => 'Пароль обязателен для заполнения',
        'min' => 'Пароль должен содержать не менее :min символов',
        'confirmed' => 'Подтверждение пароля не совпадает',
        'current' => 'Текущий пароль неверен',
    ],
    'name' => [
        'required' => 'Имя обязательно для заполнения',
        'min' => 'Имя должно содержать не менее :min символов',
        'max' => 'Имя не может превышать :max символов',
    ],
    'role' => [
        'required' => 'Роль обязательна для заполнения',
        'exists' => 'Выбранная роль не существует',
        'invalid' => 'Выбрана недействительная роль',
    ],
    'permission' => [
        'required' => 'Разрешение обязательно для заполнения',
        'exists' => 'Выбранное разрешение не существует',
        'invalid' => 'Выбрано недействительное разрешение',
    ],
    'token' => [
        'required' => 'Токен обязателен',
        'invalid' => 'Предоставлен недействительный токен',
    ],
    '2fa_code' => [
        'required' => 'Код двухфакторной аутентификации обязателен',
        'digits' => 'Код двухфакторной аутентификации должен содержать :digits цифр',
        'invalid' => 'Недействительный код двухфакторной аутентификации',
    ],
    'provider' => [
        'required' => 'Провайдер OAuth обязателен',
        'supported' => 'Выбранный провайдер OAuth не поддерживается',
    ],
    'phone' => [
        'required' => 'Номер телефона обязателен',
        'unique' => 'Этот номер телефона уже зарегистрирован',
        'format' => 'Недействительный формат номера телефона',
    ],
    'username' => [
        'required' => 'Имя пользователя обязательно',
        'unique' => 'Это имя пользователя уже используется',
        'alpha_dash' => 'Имя пользователя может содержать только буквы, цифры, дефисы и подчеркивания',
        'min' => 'Имя пользователя должно содержать не менее :min символов',
        'max' => 'Имя пользователя не может превышать :max символов',
    ],
];