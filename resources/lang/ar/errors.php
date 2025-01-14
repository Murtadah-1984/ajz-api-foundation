<?php

return [
    'unauthorized' => 'وصول غير مصرح به',
    'forbidden' => 'وصول محظور',
    'not_found' => 'المورد غير موجود',
    'validation_failed' => 'فشل التحقق من الصحة',
    'server_error' => 'خطأ في الخادم الداخلي',
    
    'auth' => [
        'invalid_credentials' => 'بيانات الاعتماد غير صالحة',
        'token_expired' => 'انتهت صلاحية رمز المصادقة',
        'token_invalid' => 'رمز المصادقة غير صالح',
        'user_not_found' => 'المستخدم غير موجود',
        'not_verified' => 'البريد الإلكتروني غير مؤكد',
        'already_verified' => 'البريد الإلكتروني مؤكد بالفعل',
    ],

    'oauth' => [
        'provider_not_found' => 'مزود OAuth غير موجود',
        'invalid_state' => 'حالة OAuth غير صالحة',
        'callback_error' => 'خطأ أثناء استدعاء OAuth',
        'user_denied' => 'تم رفض الوصول من قبل المستخدم',
    ],

    'permissions' => [
        'not_found' => 'الإذن غير موجود',
        'already_exists' => 'الإذن موجود بالفعل',
        'cannot_delete' => 'لا يمكن حذف هذا الإذن',
        'invalid_format' => 'تنسيق الإذن غير صالح',
    ],

    'roles' => [
        'not_found' => 'الدور غير موجود',
        'already_exists' => 'الدور موجود بالفعل',
        'cannot_delete' => 'لا يمكن حذف هذا الدور',
        'invalid_format' => 'تنسيق الدور غير صالح',
    ],

    'users' => [
        'not_found' => 'المستخدم غير موجود',
        'already_exists' => 'المستخدم موجود بالفعل',
        'cannot_delete' => 'لا يمكن حذف هذا المستخدم',
        'invalid_format' => 'تنسيق المستخدم غير صالح',
    ],

    'security' => [
        'ip_blocked' => 'تم حظر عنوان IP',
        'too_many_attempts' => 'محاولات كثيرة جداً',
        'invalid_2fa' => 'رمز المصادقة الثنائية غير صالح',
        'session_expired' => 'انتهت صلاحية الجلسة',
    ],
];
