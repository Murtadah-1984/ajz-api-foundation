<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Providers\Auth\OAuth;

final class FacebookProvider extends AbstractOAuthProvider
{
    protected function getAuthBaseUrl(): string
    {
        return 'https://www.facebook.com/v18.0/dialog/oauth';
    }

    protected function getTokenUrl(): string
    {
        return 'https://graph.facebook.com/v18.0/oauth/access_token';
    }

    protected function getUserUrl(): string
    {
        return 'https://graph.facebook.com/v18.0/me';
    }

    public function getProviderName(): string
    {
        return 'facebook';
    }

    public function getScopes(): array
    {
        return [
            'email',
            'public_profile',
        ];
    }

    protected function mapUserData(array $data): array
    {
        // Get detailed user data including email and picture
        try {
            $response = $this->httpClient->get($this->getUserUrl(), [
                'query' => [
                    'fields' => 'id,name,email,picture.type(large)',
                    'access_token' => $data['access_token'],
                ],
            ]);

            $userData = json_decode((string) $response->getBody(), true);

            return [
                'id' => (string) $userData['id'],
                'email' => $userData['email'] ?? null,
                'name' => $userData['name'],
                'nickname' => null,
                'avatar' => $userData['picture']['data']['url'] ?? null,
                'raw' => $userData,
            ];
        } catch (\Exception $e) {
            throw OAuthException::failedToGetUserDetails($e->getMessage());
        }
    }

    protected function getCodeFields(): array
    {
        return [
            'auth_type' => 'rerequest',
            'display' => 'popup',
        ];
    }

    public function revokeToken(string $token): void
    {
        try {
            $this->httpClient->delete('https://graph.facebook.com/v18.0/me/permissions', [
                'query' => [
                    'access_token' => $token,
                ],
            ]);
        } catch (\Exception $e) {
            throw OAuthException::failedToRevokeToken($e->getMessage());
        }
    }
}
