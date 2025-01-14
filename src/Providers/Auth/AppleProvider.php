<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Providers\Auth\OAuth;

final class AppleProvider extends AbstractOAuthProvider
{
    protected function getAuthBaseUrl(): string
    {
        return 'https://appleid.apple.com/auth/authorize';
    }

    protected function getTokenUrl(): string
    {
        return 'https://appleid.apple.com/auth/token';
    }

    protected function getUserUrl(): string
    {
        return 'https://appleid.apple.com/auth/userinfo';
    }

    public function getProviderName(): string
    {
        return 'apple';
    }

    public function getScopes(): array
    {
        return [
            'name',
            'email',
        ];
    }

    protected function mapUserData(array $data): array
    {
        return [
            'id' => $data['sub'] ?? '',
            'email' => $data['email'] ?? '',
            'name' => $data['name']['firstName'] ?? '' . ' ' . $data['name']['lastName'] ?? '',
            'nickname' => null,
            'avatar' => null,
            'raw' => $data,
        ];
    }

    protected function getCodeFields(): array
    {
        return [
            'response_mode' => 'form_post',
            'response_type' => 'code id_token',
        ];
    }
}
