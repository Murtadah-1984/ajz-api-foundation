<?php

return [
    'unauthorized' => '権限がありません',
    'forbidden' => 'アクセスが禁止されています',
    'not_found' => 'リソースが見つかりません',
    'validation_failed' => '検証に失敗しました',
    'server_error' => '内部サーバーエラー',
    
    'auth' => [
        'invalid_credentials' => '認証情報が無効です',
        'token_expired' => '認証トークンの有効期限が切れています',
        'token_invalid' => '認証トークンが無効です',
        'user_not_found' => 'ユーザーが見つかりません',
        'not_verified' => 'メールアドレスが未確認です',
        'already_verified' => 'メールアドレスは既に確認済みです',
    ],

    'oauth' => [
        'provider_not_found' => 'OAuthプロバイダーが見つかりません',
        'invalid_state' => 'OAuthの状態が無効です',
        'callback_error' => 'OAuthコールバック中にエラーが発生しました',
        'user_denied' => 'ユーザーによってアクセスが拒否されました',
    ],

    'permissions' => [
        'not_found' => '権限が見つかりません',
        'already_exists' => '権限は既に存在します',
        'cannot_delete' => 'この権限を削除できません',
        'invalid_format' => '権限のフォーマットが無効です',
    ],

    'roles' => [
        'not_found' => 'ロールが見つかりません',
        'already_exists' => 'ロールは既に存在します',
        'cannot_delete' => 'このロールを削除できません',
        'invalid_format' => 'ロールのフォーマットが無効です',
    ],

    'users' => [
        'not_found' => 'ユーザーが見つかりません',
        'already_exists' => 'ユーザーは既に存在します',
        'cannot_delete' => 'このユーザーを削除できません',
        'invalid_format' => 'ユーザーのフォーマットが無効です',
    ],

    'security' => [
        'ip_blocked' => 'IPアドレスがブロックされました',
        'too_many_attempts' => '試行回数が多すぎます',
        'invalid_2fa' => '二要素認証コードが無効です',
        'session_expired' => 'セッションの有効期限が切れました',
    ],
];
