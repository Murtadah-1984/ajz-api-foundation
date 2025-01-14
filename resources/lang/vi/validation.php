<?php

return [
    'email' => [
        'required' => 'Email là bắt buộc',
        'email' => 'Vui lòng nhập một địa chỉ email hợp lệ',
        'unique' => 'Email này đã được sử dụng',
        'exists' => 'Không tìm thấy tài khoản với email này',
    ],
    'password' => [
        'required' => 'Mật khẩu là bắt buộc',
        'min' => 'Mật khẩu phải có ít nhất :min ký tự',
        'confirmed' => 'Xác nhận mật khẩu không khớp',
        'current' => 'Mật khẩu hiện tại không đúng',
    ],
    'name' => [
        'required' => 'Tên là bắt buộc',
        'min' => 'Tên phải có ít nhất :min ký tự',
        'max' => 'Tên không được vượt quá :max ký tự',
    ],
    'role' => [
        'required' => 'Vai trò là bắt buộc',
        'exists' => 'Vai trò đã chọn không tồn tại',
        'invalid' => 'Vai trò đã chọn không hợp lệ',
    ],
    'permission' => [
        'required' => 'Quyền là bắt buộc',
        'exists' => 'Quyền đã chọn không tồn tại',
        'invalid' => 'Quyền đã chọn không hợp lệ',
    ],
    'token' => [
        'required' => 'Mã thông báo là bắt buộc',
        'invalid' => 'Mã thông báo không hợp lệ',
    ],
    '2fa_code' => [
        'required' => 'Mã xác thực hai yếu tố là bắt buộc',
        'digits' => 'Mã xác thực hai yếu tố phải có :digits chữ số',
        'invalid' => 'Mã xác thực hai yếu tố không hợp lệ',
    ],
    'provider' => [
        'required' => 'Nhà cung cấp OAuth là bắt buộc',
        'supported' => 'Nhà cung cấp OAuth đã chọn không được hỗ trợ',
    ],
    'phone' => [
        'required' => 'Số điện thoại là bắt buộc',
        'unique' => 'Số điện thoại này đã được đăng ký',
        'format' => 'Định dạng số điện thoại không hợp lệ',
    ],
    'username' => [
        'required' => 'Tên người dùng là bắt buộc',
        'unique' => 'Tên người dùng này đã được sử dụng',
        'alpha_dash' => 'Tên người dùng chỉ có thể chứa chữ cái, số, dấu gạch ngang và gạch dưới',
        'min' => 'Tên người dùng phải có ít nhất :min ký tự',
        'max' => 'Tên người dùng không được vượt quá :max ký tự',
    ],
];
