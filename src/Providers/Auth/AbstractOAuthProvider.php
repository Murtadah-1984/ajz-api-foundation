<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Providers\Auth\OAuth;

use App\Domains\Auth\OAuth\Contracts\OAuthProviderInterface;
use App\Domains\Auth\OAuth\DTOs\OAuthUserDTO;
use App\Domains\Auth\OAuth\Exceptions\OAuthException;
use App\Domains\Auth\Token\DTOs\TokenDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

abstract class AbstractOAuthProvider implements OAuthProviderInterface
{
    protected Client $httpClient;
    protected string $state;

    public function __construct()
    {
        $this->httpClient = new Client([
            'timeout' => 10,
            'connect_timeout' => 10,
        ]);
        $this->state = Str::random(40);
    }

    abstract protected function getAuthBaseUrl(): string;
    abstract protected function getTokenUrl(): string;
    abstract protected function getUserUrl(): string;
    abstract protected function mapUserData(array $data): array;

    public function getAuthorizationUrl(): string
    {
        $query = http_build_query([
            'client_id' => $this->getClientId(),
            'redirect_uri' => $this->getRedirectUrl(),
            'response_type' => 'code',
            'scope' => implode(' ', $this->getScopes()),
            'state' => $this->state,
        ]);

        return $this->getAuthBaseUrl() . '?' . $query;
    }

    public function getAccessToken(string $code): TokenDTO
    {
        try {
            $response = $this->httpClient->post($this->getTokenUrl(), [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->getClientId(),
                    'client_secret' => $this->getClientSecret(),
                    'redirect_uri' => $this->getRedirectUrl(),
                    'code' => $code,
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);

            return TokenDTO::fromArray($data);
        } catch (GuzzleException $e) {
            throw OAuthException::failedToGetAccessToken($e->getMessage());
        }
    }

    public function getUserByCode(string $code): OAuthUserDTO
    {
        $token = $this->getAccessToken($code);

        try {
            $response = $this->httpClient->get($this->getUserUrl(), [
                'headers' => [
                    'Authorization' => "Bearer {$token->accessToken}",
                    'Accept' => 'application/json',
                ],
            ]);

            $userData = json_decode((string) $response->getBody(), true);
            $mappedData = $this->mapUserData($userData);

            return OAuthUserDTO::fromArray(
                $mappedData,
                $this->getProviderName(),
                $token->accessToken,
                $token->refreshToken,
                $token->expiresIn
            );
        } catch (GuzzleException $e) {
            throw OAuthException::failedToGetUserDetails($e->getMessage());
        }
    }

    protected function getConfigValue(string $key): string
    {
        $value = config("services.{$this->getProviderName()}.$key");

        if (!$value) {
            throw OAuthException::missingConfig($key, $this->getProviderName());
        }

        return $value;
    }

    public function getClientId(): string
    {
        return $this->getConfigValue('client_id');
    }

    public function getClientSecret(): string
    {
        return $this->getConfigValue('client_secret');
    }

    public function getRedirectUrl(): string
    {
        return $this->getConfigValue('redirect');
    }
}
