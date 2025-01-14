<?php

return [
    'unauthorized' => '권한이 없습니다',
    'forbidden' => '접근이 금지되었습니다',
    'not_found' => '리소스를 찾을 수 없습니다',
    'validation_failed' => '유효성 검사에 실패했습니다',
    'server_error' => '내부 서버 오류',
    
    'auth' => [
        'invalid_credentials' => '잘못된 인증 정보입니다',
        'token_expired' => '인증 토큰이 만료되었습니다',
        'token_invalid' => '유효하지 않은 인증 토큰입니다',
        'user_not_found' => '사용자를 찾을 수 없습니다',
        'not_verified' => '이메일이 확인되지 않았습니다',
        'already_verified' => '이메일이 이미 확인되었습니다',
    ],

    'oauth' => [
        'provider_not_found' => 'OAuth 제공자를 찾을 수 없습니다',
        'invalid_state' => '유효하지 않은 OAuth 상태입니다',
        'callback_error' => 'OAuth 콜백 중 오류가 발생했습니다',
        'user_denied' => '사용자가 접근을 거부했습니다',
    ],

    'permissions' => [
        'not_found' => '권한을 찾을 수 없습니다',
        'already_exists' => '권한이 이미 존재합니다',
        'cannot_delete' => '이 권한을 삭제할 수 없습니다',
        'invalid_format' => '유효하지 않은 권한 형식입니다',
    ],

    'roles' => [
        'not_found' => '역할을 찾을 수 없습니다',
        'already_exists' => '역할이 이미 존재합니다',
        'cannot_delete' => '이 역할을 삭제할 수 없습니다',
        'invalid_format' => '유효하지 않은 역할 형식입니다',
    ],

    'users' => [
        'not_found' => '사용자를 찾을 수 없습니다',
        'already_exists' => '사용자가 이미 존재합니다',
        'cannot_delete' => '이 사용자를 삭제할 수 없습니다',
        'invalid_format' => '유효하지 않은 사용자 형식입니다',
    ],

    'security' => [
        'ip_blocked' => 'IP 주소가 차단되었습니다',
        'too_many_attempts' => '너무 많은 시도가 있었습니다',
        'invalid_2fa' => '유효하지 않은 2단계 인증 코드입니다',
        'session_expired' => '세션이 만료되었습니다',
    ],
];
