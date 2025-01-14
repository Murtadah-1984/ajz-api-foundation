<?php

return [
    'unauthorized' => 'Yetkisiz erişim',
    'forbidden' => 'Erişim yasak',
    'not_found' => 'Kaynak bulunamadı',
    'validation_failed' => 'Doğrulama başarısız',
    'server_error' => 'Sunucu hatası',
    
    'auth' => [
        'invalid_credentials' => 'Geçersiz kimlik bilgileri',
        'token_expired' => 'Kimlik doğrulama belirteci süresi doldu',
        'token_invalid' => 'Geçersiz kimlik doğrulama belirteci',
        'user_not_found' => 'Kullanıcı bulunamadı',
        'not_verified' => 'E-posta doğrulanmadı',
        'already_verified' => 'E-posta zaten doğrulandı',
    ],

    'oauth' => [
        'provider_not_found' => 'OAuth sağlayıcısı bulunamadı',
        'invalid_state' => 'Geçersiz OAuth durumu',
        'callback_error' => 'OAuth geri çağrısı sırasında hata',
        'user_denied' => 'Kullanıcı erişimi reddetti',
    ],

    'permissions' => [
        'not_found' => 'İzin bulunamadı',
        'already_exists' => 'İzin zaten mevcut',
        'cannot_delete' => 'Bu izin silinemez',
        'invalid_format' => 'Geçersiz izin formatı',
    ],

    'roles' => [
        'not_found' => 'Rol bulunamadı',
        'already_exists' => 'Rol zaten mevcut',
        'cannot_delete' => 'Bu rol silinemez',
        'invalid_format' => 'Geçersiz rol formatı',
    ],

    'users' => [
        'not_found' => 'Kullanıcı bulunamadı',
        'already_exists' => 'Kullanıcı zaten mevcut',
        'cannot_delete' => 'Bu kullanıcı silinemez',
        'invalid_format' => 'Geçersiz kullanıcı formatı',
    ],

    'security' => [
        'ip_blocked' => 'IP adresi engellendi',
        'too_many_attempts' => 'Çok fazla deneme',
        'invalid_2fa' => 'Geçersiz iki faktörlü kimlik doğrulama kodu',
        'session_expired' => 'Oturum süresi doldu',
    ],
];
