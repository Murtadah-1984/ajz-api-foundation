<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Providers\Auth\OAuth;

final class GoogleProvider extends AbstractOAuthProvider
{
    protected function getAuthBaseUrl(): string
    {
        return 'https://accounts.google.com/o/oauth2/v2/auth';
    }

    protected function getTokenUrl(): string
    {
        return 'https://oauth2.googleapis.com/token';
    }

    protected function getUserUrl(): string
    {
        return 'https://www.googleapis.com/oauth2/v3/userinfo';
    }

    public function getProviderName(): string
    {
        return 'google';
    }

    public function getScopes(): array
    {
        return [
            'openid',
            'profile',
            'email',
            'https://www.googleapis.com/auth/gmail.readonly'
        ];
    }

    protected function mapUserData(array $data): array
    {
        try {
            $response = $this->httpClient->get($this->getUserUrl(), [
                'headers' => [
                    'Authorization' => "Bearer {$data['access_token']}",
                ],
            ]);

            $userData = json_decode((string) $response->getBody(), true);

            return [
                'id' => $userData['sub'],
                'email' => $userData['email'],
                'name' => $userData['name'],
                'nickname' => $userData['given_name'],
                'avatar' => $userData['picture'],
                'raw' => $userData,
            ];
        } catch (\Exception $e) {
            throw OAuthException::failedToGetUserDetails($e->getMessage());
        }
    }

    protected function getCodeFields(): array
    {
        return [
            'access_type' => 'offline',
            'prompt' => 'consent select_account',
        ];
    }

    public function revokeToken(string $token): void
    {
        try {
            $this->httpClient->post('https://oauth2.googleapis.com/revoke', [
                'form_params' => [
                    'token' => $token,
                ],
            ]);
        } catch (\Exception $e) {
            throw OAuthException::failedToRevokeToken($e->getMessage());
        }
    }

    public function refreshToken(string $refreshToken): TokenDTO
    {
        try {
            $response = $this->httpClient->post($this->getTokenUrl(), [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->getClientId(),
                    'client_secret' => $this->getClientSecret(),
                    'refresh_token' => $refreshToken,
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);

            return TokenDTO::fromArray($data);
        } catch (\Exception $e) {
            throw OAuthException::failedToRefreshToken($e->getMessage());
        }
    }
}
