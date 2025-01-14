<?php

return [
    'email' => [
        'required' => 'メールアドレスは必須です',
        'email' => '有効なメールアドレスを入力してください',
        'unique' => 'このメールアドレスは既に使用されています',
        'exists' => 'このメールアドレスのアカウントが見つかりません',
    ],
    'password' => [
        'required' => 'パスワードは必須です',
        'min' => 'パスワードは:min文字以上である必要があります',
        'confirmed' => 'パスワードの確認が一致しません',
        'current' => '現在のパスワードが正しくありません',
    ],
    'name' => [
        'required' => '名前は必須です',
        'min' => '名前は:min文字以上である必要があります',
        'max' => '名前は:max文字以下である必要があります',
    ],
    'role' => [
        'required' => 'ロールは必須です',
        'exists' => '選択されたロールは存在しません',
        'invalid' => '無効なロールが選択されました',
    ],
    'permission' => [
        'required' => '権限は必須です',
        'exists' => '選択された権限は存在しません',
        'invalid' => '無効な権限が選択されました',
    ],
    'token' => [
        'required' => 'トークンは必須です',
        'invalid' => '無効なトークンが提供されました',
    ],
    '2fa_code' => [
        'required' => '二要素認証コードは必須です',
        'digits' => '二要素認証コードは:digits桁の数字である必要があります',
        'invalid' => '二要素認証コードが無効です',
    ],
    'provider' => [
        'required' => 'OAuthプロバイダーは必須です',
        'supported' => '選択されたOAuthプロバイダーはサポートされていません',
    ],
    'phone' => [
        'required' => '電話番号は必須です',
        'unique' => 'この電話番号は既に登録されています',
        'format' => '電話番号の形式が無効です',
    ],
    'username' => [
        'required' => 'ユーザー名は必須です',
        'unique' => 'このユーザー名は既に使用されています',
        'alpha_dash' => 'ユーザー名には文字、数字、ダッシュ、アンダースコアのみ使用できます',
        'min' => 'ユーザー名は:min文字以上である必要があります',
        'max' => 'ユーザー名は:max文字以下である必要があります',
    ],
];
