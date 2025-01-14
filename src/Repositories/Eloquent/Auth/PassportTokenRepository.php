<?php

namespace MyDDD\AuthDomain\Repositories\Eloquent\Auth;

use MyDDD\AuthDomain\DataTransferObjects\Auth\TokenData;
use MyDDD\AuthDomain\Exceptions\TokenExpiredException;
use Laravel\Passport\TokenRepository as PassportRepository;
use Laravel\Passport\RefreshTokenRepository;
use Carbon\Carbon;

class PassportTokenRepository implements TokenRepositoryInterface
{
    public function __construct(
        private PassportRepository $passportRepository,
        private RefreshTokenRepository $refreshTokenRepository
    ) {}

    public function findToken(string $token): ?TokenData
    {
        $accessToken = $this->passportRepository->find($token);

        if (!$accessToken || $accessToken->revoked || $accessToken->expires_at->isPast()) {
            return null;
        }

        $refreshToken = $this->refreshTokenRepository->findForAccessToken($accessToken->id);

        return new TokenData(
            accessToken: $token,
            refreshToken: $refreshToken?->id,
            expiresIn: $accessToken->expires_at->diffInSeconds(now()),
            tokenType: 'Bearer'
        );
    }

    public function refreshToken(string $refreshToken): TokenData
    {
        $oldRefreshToken = $this->refreshTokenRepository->find($refreshToken);

        if (!$oldRefreshToken || $oldRefreshToken->revoked || $oldRefreshToken->expires_at->isPast()) {
            throw new TokenExpiredException();
        }

        // Get the old access token
        $oldAccessToken = $this->passportRepository->find($oldRefreshToken->access_token_id);

        if (!$oldAccessToken) {
            throw new TokenExpiredException();
        }

        // Create new access token
        $newAccessToken = $this->passportRepository->createPersonalAccessToken(
            $oldAccessToken->user_id,
            $oldAccessToken->client_id,
            $oldAccessToken->scopes,
            Carbon::now()->addDays(15)
        );

        // Create new refresh token
        $newRefreshToken = $this->refreshTokenRepository->create([
            'id' => \Illuminate\Support\Str::random(100),
            'access_token_id' => $newAccessToken->id,
            'expires_at' => Carbon::now()->addDays(30),
        ]);

        // Revoke old tokens
        $this->passportRepository->revokeAccessToken($oldAccessToken->id);
        $this->refreshTokenRepository->revokeRefreshToken($oldRefreshToken->id);

        return new TokenData(
            accessToken: $newAccessToken->id,
            refreshToken: $newRefreshToken->id,
            expiresIn: $newAccessToken->expires_at->diffInSeconds(now()),
            tokenType: 'Bearer'
        );
    }

    public function revokeToken(string $token): void
    {
        $accessToken = $this->passportRepository->find($token);

        if ($accessToken) {
            $this->passportRepository->revokeAccessToken($accessToken->id);
            $this->refreshTokenRepository->revokeRefreshTokensByAccessTokenId($accessToken->id);
        }
    }

    public function storeToken(TokenData $tokenData): void
    {
        // No need to implement as Passport handles token storage internally
        // This method is part of the interface for other potential implementations
        // that might need custom token storage
    }
}
