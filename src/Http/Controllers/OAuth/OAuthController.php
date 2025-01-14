<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Controllers\OAuth;

use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\Repositories\Eloquent\Tokens\TokenRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\TokenRepository as PassportTokenRepository;
use PragmaRX\Google2FA\Google2FA;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class OAuthController extends Controller
{
    public function __construct(
        private readonly TokenRepository $tokenRepository,
        private readonly PassportTokenRepository $passportTokenRepository,
        private readonly Google2FA $google2fa
    ) {}

    /**
     * Issue a new access token.
     */
    public function issueToken(Request $request): JsonResponse
    {
        try {
            // Check for IP blocking
            if ($this->tokenRepository->isIpBlocked($request->ip())) {
                Log::warning('Blocked IP attempted token issuance', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                return response()->json([
                    'message' => 'Too many failed attempts. Please try again later.',
                ], Response::HTTP_TOO_MANY_REQUESTS);
            }

            // Validate credentials
            if (!auth()->attempt($request->only('email', 'password'))) {
                $this->tokenRepository->trackFailedAttempt($request->ip());
                
                Log::warning('Failed login attempt', [
                    'ip' => $request->ip(),
                    'email' => $request->input('email'),
                ]);

                return response()->json([
                    'message' => 'Invalid credentials',
                ], Response::HTTP_UNAUTHORIZED);
            }

            /** @var User $user */
            $user = auth()->user();

            // Check 2FA if enabled
            if ($user->two_factor_enabled) {
                return $this->handle2FA($request, $user);
            }

            // Create token with circuit breaker pattern
            $token = Cache::remember(
                "token_creation:{$user->id}",
                now()->addSeconds(30),
                fn () => $this->tokenRepository->createToken(
                    $user,
                    $request->input('scopes', []),
                    $request->input('device_name')
                )
            );

            // Update user login info
            $user->updateLoginInfo($request->ip());

            // Log successful authentication
            Log::info('User authenticated successfully', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'token' => $token->accessToken,
                'expires_at' => $token->expires_at,
                'token_type' => 'Bearer',
            ]);
        } catch (Throwable $e) {
            Log::error('Token issuance failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Authentication failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Refresh an access token.
     */
    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $token = $this->passportTokenRepository->find($request->input('token_id'));

            if (!$token || $token->revoked || $token->hasExpired()) {
                Log::warning('Invalid token refresh attempt', [
                    'token_id' => $request->input('token_id'),
                    'ip' => $request->ip(),
                ]);

                return response()->json([
                    'message' => 'Invalid token',
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Implement retry mechanism for token rotation
            $attempts = 0;
            $maxAttempts = 3;
            
            do {
                try {
                    $newToken = $this->tokenRepository->rotateToken($token);
                    break;
                } catch (Throwable $e) {
                    $attempts++;
                    if ($attempts === $maxAttempts) {
                        throw $e;
                    }
                    usleep(100000); // 100ms delay between attempts
                }
            } while ($attempts < $maxAttempts);

            return response()->json([
                'token' => $newToken->accessToken,
                'expires_at' => $newToken->expires_at,
                'token_type' => 'Bearer',
            ]);
        } catch (Throwable $e) {
            Log::error('Token refresh failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Token refresh failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Revoke an access token.
     */
    public function revokeToken(Request $request): JsonResponse
    {
        try {
            $token = $this->passportTokenRepository->find($request->input('token_id'));

            if (!$token || $token->revoked) {
                Log::warning('Invalid token revocation attempt', [
                    'token_id' => $request->input('token_id'),
                    'ip' => $request->ip(),
                ]);

                return response()->json([
                    'message' => 'Invalid token',
                ], Response::HTTP_UNAUTHORIZED);
            }

            $token->revoke();

            Log::info('Token revoked', [
                'token_id' => $token->id,
                'user_id' => $token->user_id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Token revoked successfully',
            ]);
        } catch (Throwable $e) {
            Log::error('Token revocation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Token revocation failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Handle 2FA verification.
     */
    private function handle2FA(Request $request, User $user): JsonResponse
    {
        $code = $request->input('two_factor_code');
        
        if (!$code) {
            return response()->json([
                'message' => '2FA code required',
                'requires_2fa' => true,
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Verify 2FA code with rate limiting
        $key = "2fa_attempts:{$user->id}";
        $attempts = Cache::tags(['2fa_attempts'])->get($key, 0);

        if ($attempts >= 5) {
            Log::warning('Too many 2FA attempts', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Too many 2FA attempts. Please try again later.',
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        Cache::tags(['2fa_attempts'])->put($key, $attempts + 1, now()->addHour());

        // Verify code
        if (!$this->google2fa->verifyKey(
            decrypt($user->two_factor_secret),
            $code
        )) {
            Log::warning('Invalid 2FA code', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Invalid 2FA code',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Create token after successful 2FA
        $token = $this->tokenRepository->createToken(
            $user,
            $request->input('scopes', []),
            $request->input('device_name')
        );

        return response()->json([
            'token' => $token->accessToken,
            'expires_at' => $token->expires_at,
            'token_type' => 'Bearer',
        ]);
    }
}
