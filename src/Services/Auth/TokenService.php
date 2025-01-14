<?php

namespace MyDDD\AuthDomain\Services\Auth;

use MyDDD\AuthDomain\DataTransferObjects\Auth\TokenData;
use MyDDD\AuthDomain\DTOs\OAuthUserDTO;
use MyDDD\AuthDomain\Exceptions\TokenExpiredException;
use MyDDD\AuthDomain\Repositories\Interfaces\TokenRepositoryInterface;
use MyDDD\AuthDomain\Models\User;
use Laravel\Passport\TokenRepository as PassportTokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use Illuminate\Support\Str;

class TokenService
{
    public function __construct(
        private TokenRepositoryInterface $tokenRepository,
        private PassportTokenRepository $passportTokenRepository,
        private RefreshTokenRepository $refreshTokenRepository
    ) {}

    public function validateToken(string $token): TokenData
    {
        $tokenData = $this->tokenRepository->findToken($token);
        
        if (!$tokenData) {
            throw new TokenExpiredException();
        }
        
        return $tokenData;
    }

    public function refreshToken(string $token): TokenData
    {
        return $this->tokenRepository->refreshToken($token);
    }

    public function revokeToken(string $token): void
    {
        // Revoke both access and refresh tokens
        $accessToken = $this->passportTokenRepository->find($token);
        
        if ($accessToken) {
            $this->passportTokenRepository->revokeAccessToken($accessToken->id);
            $this->refreshTokenRepository->revokeRefreshTokensByAccessTokenId($accessToken->id);
        }
        
        $this->tokenRepository->revokeToken($token);
    }

    public function createTokenForUser(User $user, array $scopes = []): TokenData
    {
        $token = $user->createToken(
            name: 'Personal Access Token',
            scopes: $scopes
        );

        return new TokenData(
            accessToken: $token->accessToken,
            refreshToken: $token->refreshToken,
            expiresIn: $token->token->expires_at->diffInSeconds(now()),
            tokenType: 'Bearer'
        );
    }

    public function createTokenForOAuthUser(OAuthUserDTO $oauthUser): TokenData
    {
        // Find or create user based on OAuth data
        $user = User::updateOrCreate(
            [
                'email' => $oauthUser->email,
            ],
            [
                'name' => $oauthUser->name,
                'password' => bcrypt(Str::random(16)), // Random password for OAuth users
                $oauthUser->provider . '_id' => $oauthUser->providerId,
            ]
        );

        // Create token with OAuth-specific scopes if needed
        return $this->createTokenForUser($user, ['oauth-user']);
    }

    public function createClientToken(string $clientId, string $clientSecret, array $scopes = []): TokenData
    {
        // Implement client credentials grant
        $token = $this->passportTokenRepository->createPersonalAccessToken(
            $clientId,
            $clientSecret,
            'API Client Token',
            $scopes
        );

        return new TokenData(
            accessToken: $token->accessToken,
            refreshToken: null, // Client credentials don't use refresh tokens
            expiresIn: $token->token->expires_at->diffInSeconds(now()),
            tokenType: 'Bearer'
        );
    }
}
