<?php

namespace MyDDD\AuthDomain\Repositories\Eloquent\Auth;

use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\DataTransferObjects\Auth\TokenData;
use MyDDD\AuthDomain\Exceptions\TokenExpiredException;
use Illuminate\Support\{Facades\Redis,Str};


class RedisTokenRepository implements TokenRepositoryInterface
{
    private const TOKEN_PREFIX = 'auth_token:';
    private const TOKEN_TTL = 3600; // 1 hour

    public function createToken(User $user, array $abilities = ['*']): TokenData
    {
        $accessToken = Str::random(40);
        $refreshToken = Str::random(40);
        
        Redis::setex(
            self::TOKEN_PREFIX . $accessToken,
            self::TOKEN_TTL,
            json_encode([
                'user_id' => $user->id,
                'abilities' => $abilities,
                'refresh_token' => $refreshToken
            ])
        );

        return new TokenData(
            access_token: $accessToken,
            refresh_token: $refreshToken,
            expires_in: self::TOKEN_TTL,
            token_type: 'Bearer'
        );
    }

    public function revokeToken(string $token): void
    {
        Redis::del(self::TOKEN_PREFIX . $token);
    }

    public function revokeAllTokens(User $user): void
    {
        $pattern = self::TOKEN_PREFIX . '*';
        $keys = Redis::keys($pattern);
        
        foreach ($keys as $key) {
            $data = json_decode(Redis::get($key), true);
            if ($data && $data['user_id'] === $user->id) {
                Redis::del($key);
            }
        }
    }

    public function findToken(string $token): ?TokenData
    {
        $data = Redis::get(self::TOKEN_PREFIX . $token);
        
        if (!$data) {
            return null;
        }

        $tokenData = json_decode($data, true);
        
        return new TokenData(
            access_token: $token,
            refresh_token: $tokenData['refresh_token'],
            expires_in: Redis::ttl(self::TOKEN_PREFIX . $token),
            token_type: 'Bearer'
        );
    }

    public function refreshToken(string $token): TokenData
    {
        $existingToken = $this->findToken($token);
        
        if (!$existingToken) {
            throw new TokenExpiredException();
        }

        $this->revokeToken($token);
        
        return $this->createToken(
            User::findOrFail(json_decode(Redis::get(self::TOKEN_PREFIX . $token), true)['user_id'])
        );
    }
}
