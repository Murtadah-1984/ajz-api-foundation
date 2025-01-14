<?php

return [
    'email' => [
        'required' => 'E-posta adresi gereklidir',
        'email' => 'Lütfen geçerli bir e-posta adresi girin',
        'unique' => 'Bu e-posta adresi zaten kullanımda',
        'exists' => 'Bu e-posta adresiyle kayıtlı hesap bulunamadı',
    ],
    'password' => [
        'required' => 'Şifre gereklidir',
        'min' => 'Şifre en az :min karakter uzunluğunda olmalıdır',
        'confirmed' => 'Şifre onayı eşleşmiyor',
        'current' => 'Mevcut şifre yanlış',
    ],
    'name' => [
        'required' => 'İsim gereklidir',
        'min' => 'İsim en az :min karakter uzunluğunda olmalıdır',
        'max' => 'İsim :max karakteri geçemez',
    ],
    'role' => [
        'required' => 'Rol gereklidir',
        'exists' => 'Seçilen rol mevcut değil',
        'invalid' => 'Geçersiz rol seçildi',
    ],
    'permission' => [
        'required' => 'İzin gereklidir',
        'exists' => 'Seçilen izin mevcut değil',
        'invalid' => 'Geçersiz izin seçildi',
    ],
    'token' => [
        'required' => 'Belirteç gereklidir',
        'invalid' => 'Geçersiz belirteç sağlandı',
    ],
    '2fa_code' => [
        'required' => 'İki faktörlü kimlik doğrulama kodu gereklidir',
        'digits' => 'İki faktörlü kimlik doğrulama kodu :digits haneli olmalıdır',
        'invalid' => 'Geçersiz iki faktörlü kimlik doğrulama kodu',
    ],
    'provider' => [
        'required' => 'OAuth sağlayıcısı gereklidir',
        'supported' => 'Seçilen OAuth sağlayıcısı desteklenmiyor',
    ],
    'phone' => [
        'required' => 'Telefon numarası gereklidir',
        'unique' => 'Bu telefon numarası zaten kayıtlı',
        'format' => 'Geçersiz telefon numarası formatı',
    ],
    'username' => [
        'required' => 'Kullanıcı adı gereklidir',
        'unique' => 'Bu kullanıcı adı zaten kullanımda',
        'alpha_dash' => 'Kullanıcı adı sadece harf, rakam, tire ve alt çizgi içerebilir',
        'min' => 'Kullanıcı adı en az :min karakter uzunluğunda olmalıdır',
        'max' => 'Kullanıcı adı :max karakteri geçemez',
    ],
];
