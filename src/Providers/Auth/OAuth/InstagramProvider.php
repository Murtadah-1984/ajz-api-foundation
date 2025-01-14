<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Infrastructure\OAuthProviders;

final class InstagramProvider extends AbstractOAuthProvider
{
    protected function getAuthBaseUrl(): string
    {
        return 'https://api.instagram.com/oauth/authorize';
    }

    protected function getTokenUrl(): string
    {
        return 'https://api.instagram.com/oauth/access_token';
    }

    protected function getUserUrl(): string
    {
        return 'https://graph.instagram.com/me';
    }

    public function getProviderName(): string
    {
        return 'instagram';
    }

    public function getScopes(): array
    {
        return [
            'basic',
            'public_content',
        ];
    }

    protected function mapUserData(array $data): array
    {
        // First get the long-lived token
        $longLivedToken = $this->getLongLivedToken($data['access_token']);
        
        // Get detailed user data with the long-lived token
        try {
            $response = $this->httpClient->get($this->getUserUrl(), [
                'query' => [
                    'fields' => 'id,username,account_type,media_count',
                    'access_token' => $longLivedToken,
                ],
            ]);

            $userData = json_decode((string) $response->getBody(), true);

            return [
                'id' => (string) $userData['id'],
                'email' => null, // Instagram API doesn't provide email
                'name' => $userData['username'],
                'nickname' => $userData['username'],
                'avatar' => null, // Need business account for profile picture
                'raw' => $userData,
            ];
        } catch (\Exception $e) {
            throw OAuthException::failedToGetUserDetails($e->getMessage());
        }
    }

    private function getLongLivedToken(string $shortLivedToken): string
    {
        try {
            $response = $this->httpClient->get('https://graph.instagram.com/access_token', [
                'query' => [
                    'grant_type' => 'ig_exchange_token',
                    'client_secret' => $this->getClientSecret(),
                    'access_token' => $shortLivedToken,
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
            return $data['access_token'];
        } catch (\Exception $e) {
            throw OAuthException::failedToGetAccessToken($e->getMessage());
        }
    }
}
