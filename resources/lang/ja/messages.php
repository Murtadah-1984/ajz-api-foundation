<?php

return [
    // Authentication
    'login' => [
        'success' => 'ログインに成功しました',
        'failed' => '認証情報が無効です',
        'throttle' => 'ログイン試行回数が多すぎます。:seconds秒後に再試行してください。',
        'not_verified' => 'アカウントが未確認です。',
        'already_logged_in' => '既にログインしています。',
    ],
    'logout' => [
        'success' => 'ログアウトに成功しました',
    ],
    'register' => [
        'success' => '登録が完了しました',
        'failed' => '登録に失敗しました',
        'email_taken' => 'このメールアドレスは既に使用されています',
    ],
    'password' => [
        'reset' => [
            'success' => 'パスワードがリセットされました',
            'failed' => 'パスワードのリセットに失敗しました',
            'token_invalid' => 'パスワードリセットトークンが無効です',
        ],
        'requirements' => [
            'length' => 'パスワードは:length文字以上である必要があります',
            'special' => 'パスワードは特殊文字を1つ以上含む必要があります',
            'number' => 'パスワードは数字を1つ以上含む必要があります',
            'mixed_case' => 'パスワードは大文字と小文字を含む必要があります',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => '二要素認証が有効になりました',
        'disabled' => '二要素認証が無効になりました',
        'verify' => '二要素認証コードを確認してください',
        'invalid' => '二要素認証コードが無効です',
    ],

    // OAuth
    'oauth' => [
        'success' => ':providerでの認証に成功しました',
        'failed' => ':providerでの認証に失敗しました',
        'email_required' => '認証にはメールアドレスへのアクセスが必要です',
        'invalid_state' => 'OAuth状態が無効です',
        'already_linked' => 'アカウントは既に:providerと連携しています',
    ],

    // Permissions
    'permissions' => [
        'created' => '権限が作成されました',
        'updated' => '権限が更新されました',
        'deleted' => '権限が削除されました',
        'assigned' => '権限が割り当てられました',
        'revoked' => '権限が取り消されました',
    ],

    // Roles
    'roles' => [
        'created' => 'ロールが作成されました',
        'updated' => 'ロールが更新されました',
        'deleted' => 'ロールが削除されました',
        'assigned' => 'ロールが割り当てられました',
        'revoked' => 'ロールが取り消されました',
    ],

    // Users
    'users' => [
        'created' => 'ユーザーが作成されました',
        'updated' => 'ユーザーが更新されました',
        'deleted' => 'ユーザーが削除されました',
    ],

    // Security
    'security' => [
        'ip_blocked' => '失敗が多すぎるため、IPアドレスがブロックされました',
        'session_expired' => 'セッションの有効期限が切れました',
        'invalid_token' => 'トークンが無効または期限切れです',
        'csrf_token_mismatch' => 'CSRFトークンが無効です',
    ],
];
