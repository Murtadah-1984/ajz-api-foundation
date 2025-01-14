<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Providers\Auth\OAuth;

final class GitHubProvider extends AbstractOAuthProvider
{
    protected function getAuthBaseUrl(): string
    {
        return 'https://github.com/login/oauth/authorize';
    }

    protected function getTokenUrl(): string
    {
        return 'https://github.com/login/oauth/access_token';
    }

    protected function getUserUrl(): string
    {
        return 'https://api.github.com/user';
    }

    public function getProviderName(): string
    {
        return 'github';
    }

    public function getScopes(): array
    {
        return [
            'read:user',
            'user:email',
        ];
    }

    protected function mapUserData(array $data): array
    {
        // Get primary email since GitHub API returns email as null if user has hidden it
        if (empty($data['email'])) {
            try {
                $response = $this->httpClient->get('https://api.github.com/user/emails', [
                    'headers' => [
                        'Authorization' => "Bearer {$data['access_token']}",
                        'Accept' => 'application/vnd.github.v3+json',
                    ],
                ]);

                $emails = json_decode((string) $response->getBody(), true);
                foreach ($emails as $email) {
                    if ($email['primary'] === true && $email['verified'] === true) {
                        $data['email'] = $email['email'];
                        break;
                    }
                }
            } catch (\Exception $e) {
                // Silently fail as email is optional
            }
        }

        return [
            'id' => (string) $data['id'],
            'email' => $data['email'] ?? '',
            'name' => $data['name'] ?? $data['login'],
            'nickname' => $data['login'],
            'avatar' => $data['avatar_url'],
            'raw' => $data,
        ];
    }

    public function revokeToken(string $token): void
    {
        try {
            $this->httpClient->delete('https://api.github.com/applications/' . $this->getClientId() . '/token', [
                'auth' => [$this->getClientId(), $this->getClientSecret()],
                'headers' => [
                    'Accept' => 'application/vnd.github.v3+json',
                ],
                'json' => [
                    'access_token' => $token,
                ],
            ]);
        } catch (\Exception $e) {
            throw OAuthException::failedToRevokeToken($e->getMessage());
        }
    }

    protected function getHeaders(): array
    {
        return [
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => 'MyDDD-API OAuth Client',
        ];
    }
}
