<?php

return [
    // Authentication
    'login' => [
        'success' => 'Başarıyla giriş yapıldı',
        'failed' => 'Geçersiz kimlik bilgileri',
        'throttle' => 'Çok fazla giriş denemesi. Lütfen :seconds saniye sonra tekrar deneyin.',
        'not_verified' => 'Hesabınız doğrulanmadı.',
        'already_logged_in' => 'Zaten giriş yapmış durumdasınız.',
    ],
    'logout' => [
        'success' => 'Başarıyla çıkış yapıldı',
    ],
    'register' => [
        'success' => 'Kayıt başarıyla tamamlandı',
        'failed' => 'Kayıt başarısız oldu',
        'email_taken' => 'Bu e-posta adresi zaten kullanımda',
    ],
    'password' => [
        'reset' => [
            'success' => 'Şifre başarıyla sıfırlandı',
            'failed' => 'Şifre sıfırlanamadı',
            'token_invalid' => 'Geçersiz şifre sıfırlama belirteci',
        ],
        'requirements' => [
            'length' => 'Şifre en az :length karakter uzunluğunda olmalıdır',
            'special' => 'Şifre en az bir özel karakter içermelidir',
            'number' => 'Şifre en az bir rakam içermelidir',
            'mixed_case' => 'Şifre büyük ve küçük harf içermelidir',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => 'İki faktörlü kimlik doğrulama etkinleştirildi',
        'disabled' => 'İki faktörlü kimlik doğrulama devre dışı bırakıldı',
        'verify' => 'Lütfen iki faktörlü kimlik doğrulama kodunuzu doğrulayın',
        'invalid' => 'Geçersiz iki faktörlü kimlik doğrulama kodu',
    ],

    // OAuth
    'oauth' => [
        'success' => ':provider ile başarıyla kimlik doğrulandı',
        'failed' => ':provider ile kimlik doğrulama başarısız oldu',
        'email_required' => 'Kimlik doğrulama için e-posta erişimi gerekli',
        'invalid_state' => 'Geçersiz OAuth durumu',
        'already_linked' => 'Hesap zaten :provider ile bağlantılı',
    ],

    // Permissions
    'permissions' => [
        'created' => 'İzin başarıyla oluşturuldu',
        'updated' => 'İzin başarıyla güncellendi',
        'deleted' => 'İzin başarıyla silindi',
        'assigned' => 'İzin başarıyla atandı',
        'revoked' => 'İzin başarıyla kaldırıldı',
    ],

    // Roles
    'roles' => [
        'created' => 'Rol başarıyla oluşturuldu',
        'updated' => 'Rol başarıyla güncellendi',
        'deleted' => 'Rol başarıyla silindi',
        'assigned' => 'Rol başarıyla atandı',
        'revoked' => 'Rol başarıyla kaldırıldı',
    ],

    // Users
    'users' => [
        'created' => 'Kullanıcı başarıyla oluşturuldu',
        'updated' => 'Kullanıcı başarıyla güncellendi',
        'deleted' => 'Kullanıcı başarıyla silindi',
    ],

    // Security
    'security' => [
        'ip_blocked' => 'IP adresiniz çok fazla başarısız deneme nedeniyle engellendi',
        'session_expired' => 'Oturumunuzun süresi doldu',
        'invalid_token' => 'Geçersiz veya süresi dolmuş belirteç',
        'csrf_token_mismatch' => 'Geçersiz CSRF belirteci',
    ],
];
