<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Repositories\Eloquent\Tokens;

use MyDDD\AuthDomain\Models\{Token, User};
use Illuminate\Support\Facades\{Cache,DB,Log};
use Laravel\Passport\TokenRepository as PassportTokenRepository;
use Throwable;

final class TokenRepository extends PassportTokenRepository
{
    /**
     * Create a new access token.
     */
    public function createToken(User $user, array $scopes = [], ?string $name = null): Token
    {
        try {
            DB::beginTransaction();

            $token = new Token([
                'name' => $name,
                'scopes' => $scopes,
                'expires_at' => now()->addDays(15),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $token->user()->associate($user);
            $token->save();

            $this->logTokenCreation($token);
            
            DB::commit();

            return $token;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to create token', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Rotate an existing token.
     */
    public function rotateToken(Token $token): Token
    {
        try {
            DB::beginTransaction();

            // Create new token
            $newToken = $this->createToken(
                $token->user,
                $token->scopes,
                $token->name
            );

            // Revoke old token
            $token->revoke();
            
            $this->logTokenRotation($token, $newToken);

            DB::commit();

            return $newToken;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to rotate token', [
                'token_id' => $token->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Check if an IP is blocked.
     */
    public function isIpBlocked(string $ip): bool
    {
        return Cache::tags(['ip_blocking'])->get("blocked_ips:{$ip}", false);
    }

    /**
     * Block an IP address.
     */
    public function blockIp(string $ip, int $minutes = 60): void
    {
        Cache::tags(['ip_blocking'])->put(
            "blocked_ips:{$ip}",
            true,
            now()->addMinutes($minutes)
        );
        
        Log::warning('IP address blocked', [
            'ip' => $ip,
            'duration' => $minutes,
            'reason' => 'Multiple failed authentication attempts',
        ]);
    }

    /**
     * Track failed login attempts.
     */
    public function trackFailedAttempt(string $ip): void
    {
        $key = "failed_attempts:{$ip}";
        $attempts = Cache::tags(['auth_attempts'])->get($key, 0) + 1;
        
        Cache::tags(['auth_attempts'])->put($key, $attempts, now()->addHour());

        if ($attempts >= 5) {
            $this->blockIp($ip);
            
            Log::alert('Multiple failed login attempts detected', [
                'ip' => $ip,
                'attempts' => $attempts,
                'action' => 'IP blocked',
            ]);
        }
    }

    /**
     * Revoke all tokens for a user.
     */
    public function revokeAllTokens(User $user): void
    {
        try {
            DB::beginTransaction();

            $user->tokens()->update(['revoked' => true]);

            Log::info('All tokens revoked for user', [
                'user_id' => $user->id,
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to revoke all tokens', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Clean up expired tokens.
     */
    public function cleanupExpiredTokens(): void
    {
        try {
            DB::beginTransaction();

            Token::where('expires_at', '<', now())
                ->update(['revoked' => true]);

            Log::info('Expired tokens cleaned up');

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to cleanup expired tokens', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Log token creation.
     */
    private function logTokenCreation(Token $token): void
    {
        Log::info('Token created', [
            'token_id' => $token->id,
            'user_id' => $token->user_id,
            'ip_address' => $token->ip_address,
            'user_agent' => $token->user_agent,
            'expires_at' => $token->expires_at,
        ]);
    }

    /**
     * Log token rotation.
     */
    private function logTokenRotation(Token $oldToken, Token $newToken): void
    {
        Log::info('Token rotated', [
            'old_token_id' => $oldToken->id,
            'new_token_id' => $newToken->id,
            'user_id' => $oldToken->user_id,
            'reason' => 'Token rotation policy',
        ]);
    }
}
