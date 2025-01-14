<?php

return [
    'unauthorized' => 'Truy cập không được phép',
    'forbidden' => 'Truy cập bị cấm',
    'not_found' => 'Không tìm thấy tài nguyên',
    'validation_failed' => 'Xác thực thất bại',
    'server_error' => 'Lỗi máy chủ nội bộ',
    
    'auth' => [
        'invalid_credentials' => 'Thông tin đăng nhập không hợp lệ',
        'token_expired' => 'Mã xác thực đã hết hạn',
        'token_invalid' => 'Mã xác thực không hợp lệ',
        'user_not_found' => 'Không tìm thấy người dùng',
        'not_verified' => 'Email chưa được xác minh',
        'already_verified' => 'Email đã được xác minh',
    ],

    'oauth' => [
        'provider_not_found' => 'Không tìm thấy nhà cung cấp OAuth',
        'invalid_state' => 'Trạng thái OAuth không hợp lệ',
        'callback_error' => 'Lỗi trong quá trình callback OAuth',
        'user_denied' => 'Người dùng từ chối truy cập',
    ],

    'permissions' => [
        'not_found' => 'Không tìm thấy quyền',
        'already_exists' => 'Quyền đã tồn tại',
        'cannot_delete' => 'Không thể xóa quyền này',
        'invalid_format' => 'Định dạng quyền không hợp lệ',
    ],

    'roles' => [
        'not_found' => 'Không tìm thấy vai trò',
        'already_exists' => 'Vai trò đã tồn tại',
        'cannot_delete' => 'Không thể xóa vai trò này',
        'invalid_format' => 'Định dạng vai trò không hợp lệ',
    ],

    'users' => [
        'not_found' => 'Không tìm thấy người dùng',
        'already_exists' => 'Người dùng đã tồn tại',
        'cannot_delete' => 'Không thể xóa người dùng này',
        'invalid_format' => 'Định dạng người dùng không hợp lệ',
    ],

    'security' => [
        'ip_blocked' => 'Địa chỉ IP đã bị chặn',
        'too_many_attempts' => 'Quá nhiều lần thử',
        'invalid_2fa' => 'Mã xác thực hai yếu tố không hợp lệ',
        'session_expired' => 'Phiên đã hết hạn',
    ],
];
