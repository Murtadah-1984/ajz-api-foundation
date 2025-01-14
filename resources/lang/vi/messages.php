<?php

return [
    // Authentication
    'login' => [
        'success' => 'Đăng nhập thành công',
        'failed' => 'Thông tin đăng nhập không hợp lệ',
        'throttle' => 'Quá nhiều lần đăng nhập. Vui lòng thử lại sau :seconds giây.',
        'not_verified' => 'Tài khoản của bạn chưa được xác minh.',
        'already_logged_in' => 'Bạn đã đăng nhập rồi.',
    ],
    'logout' => [
        'success' => 'Đăng xuất thành công',
    ],
    'register' => [
        'success' => 'Đăng ký thành công',
        'failed' => 'Đăng ký thất bại',
        'email_taken' => 'Email này đã được sử dụng',
    ],
    'password' => [
        'reset' => [
            'success' => 'Đặt lại mật khẩu thành công',
            'failed' => 'Không thể đặt lại mật khẩu',
            'token_invalid' => 'Mã đặt lại mật khẩu không hợp lệ',
        ],
        'requirements' => [
            'length' => 'Mật khẩu phải có ít nhất :length ký tự',
            'special' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt',
            'number' => 'Mật khẩu phải chứa ít nhất một số',
            'mixed_case' => 'Mật khẩu phải chứa cả chữ hoa và chữ thường',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => 'Đã bật xác thực hai yếu tố',
        'disabled' => 'Đã tắt xác thực hai yếu tố',
        'verify' => 'Vui lòng xác minh mã xác thực hai yếu tố của bạn',
        'invalid' => 'Mã xác thực hai yếu tố không hợp lệ',
    ],

    // OAuth
    'oauth' => [
        'success' => 'Xác thực thành công với :provider',
        'failed' => 'Xác thực thất bại với :provider',
        'email_required' => 'Cần quyền truy cập email để xác thực',
        'invalid_state' => 'Trạng thái OAuth không hợp lệ',
        'already_linked' => 'Tài khoản đã được liên kết với :provider',
    ],

    // Permissions
    'permissions' => [
        'created' => 'Đã tạo quyền thành công',
        'updated' => 'Đã cập nhật quyền thành công',
        'deleted' => 'Đã xóa quyền thành công',
        'assigned' => 'Đã gán quyền thành công',
        'revoked' => 'Đã thu hồi quyền thành công',
    ],

    // Roles
    'roles' => [
        'created' => 'Đã tạo vai trò thành công',
        'updated' => 'Đã cập nhật vai trò thành công',
        'deleted' => 'Đã xóa vai trò thành công',
        'assigned' => 'Đã gán vai trò thành công',
        'revoked' => 'Đã thu hồi vai trò thành công',
    ],

    // Users
    'users' => [
        'created' => 'Đã tạo người dùng thành công',
        'updated' => 'Đã cập nhật người dùng thành công',
        'deleted' => 'Đã xóa người dùng thành công',
    ],

    // Security
    'security' => [
        'ip_blocked' => 'Địa chỉ IP của bạn đã bị chặn do quá nhiều lần thử thất bại',
        'session_expired' => 'Phiên của bạn đã hết hạn',
        'invalid_token' => 'Mã thông báo không hợp lệ hoặc đã hết hạn',
        'csrf_token_mismatch' => 'Mã CSRF không hợp lệ',
    ],
];
