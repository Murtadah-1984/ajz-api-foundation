<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Middleware;

use MyDDD\AuthDomain\Repositories\Eloquent\Tokens\TokenRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

final class OneSessionPerUser
{
    public function __construct(
        private readonly TokenRepository $tokenRepository
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            $currentToken = $request->bearerToken();
            $sessionKey = "user_session:{$user->id}";
            $activeToken = Cache::get($sessionKey);

            // If there's an active session with a different token
            if ($activeToken && $activeToken !== $currentToken) {
                // Revoke the old token
                if ($oldToken = $this->tokenRepository->findByToken($activeToken)) {
                    $this->tokenRepository->revokeAllTokens($user);
                }
            }

            // Store current token as active session
            Cache::put($sessionKey, $currentToken, now()->addDays(1));
        }

        return $next($request);
    }
}
