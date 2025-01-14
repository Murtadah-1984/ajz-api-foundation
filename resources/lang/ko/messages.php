<?php

return [
    // Authentication
    'login' => [
        'success' => '로그인에 성공했습니다',
        'failed' => '잘못된 인증 정보입니다',
        'throttle' => '너무 많은 로그인 시도가 있었습니다. :seconds초 후에 다시 시도해 주세요.',
        'not_verified' => '계정이 확인되지 않았습니다.',
        'already_logged_in' => '이미 로그인되어 있습니다.',
    ],
    'logout' => [
        'success' => '로그아웃에 성공했습니다',
    ],
    'register' => [
        'success' => '회원가입이 완료되었습니다',
        'failed' => '회원가입에 실패했습니다',
        'email_taken' => '이미 사용 중인 이메일입니다',
    ],
    'password' => [
        'reset' => [
            'success' => '비밀번호가 재설정되었습니다',
            'failed' => '비밀번호 재설정에 실패했습니다',
            'token_invalid' => '비밀번호 재설정 토큰이 유효하지 않습니다',
        ],
        'requirements' => [
            'length' => '비밀번호는 최소 :length자 이상이어야 합니다',
            'special' => '비밀번호는 최소 1개의 특수문자를 포함해야 합니다',
            'number' => '비밀번호는 최소 1개의 숫자를 포함해야 합니다',
            'mixed_case' => '비밀번호는 대문자와 소문자를 모두 포함해야 합니다',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => '2단계 인증이 활성화되었습니다',
        'disabled' => '2단계 인증이 비활성화되었습니다',
        'verify' => '2단계 인증 코드를 확인해 주세요',
        'invalid' => '유효하지 않은 2단계 인증 코드입니다',
    ],

    // OAuth
    'oauth' => [
        'success' => ':provider로 인증에 성공했습니다',
        'failed' => ':provider 인증에 실패했습니다',
        'email_required' => '인증을 위해 이메일 접근 권한이 필요합니다',
        'invalid_state' => '유효하지 않은 OAuth 상태입니다',
        'already_linked' => '이미 :provider와 연결된 계정입니다',
    ],

    // Permissions
    'permissions' => [
        'created' => '권한이 생성되었습니다',
        'updated' => '권한이 업데이트되었습니다',
        'deleted' => '권한이 삭제되었습니다',
        'assigned' => '권한이 할당되었습니다',
        'revoked' => '권한이 취소되었습니다',
    ],

    // Roles
    'roles' => [
        'created' => '역할이 생성되었습니다',
        'updated' => '역할이 업데이트되었습니다',
        'deleted' => '역할이 삭제되었습니다',
        'assigned' => '역할이 할당되었습니다',
        'revoked' => '역할이 취소되었습니다',
    ],

    // Users
    'users' => [
        'created' => '사용자가 생성되었습니다',
        'updated' => '사용자 정보가 업데이트되었습니다',
        'deleted' => '사용자가 삭제되었습니다',
    ],

    // Security
    'security' => [
        'ip_blocked' => '너무 많은 실패로 인해 IP 주소가 차단되었습니다',
        'session_expired' => '세션이 만료되었습니다',
        'invalid_token' => '유효하지 않거나 만료된 토큰입니다',
        'csrf_token_mismatch' => '유효하지 않은 CSRF 토큰입니다',
    ],
];
