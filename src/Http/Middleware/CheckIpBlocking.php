<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Middleware;

use MyDDD\AuthDomain\Repositories\Eloquent\Tokens\TokenRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class CheckIpBlocking
{
    public function __construct(
        private readonly TokenRepository $tokenRepository
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        if ($this->tokenRepository->isIpBlocked($ip)) {
            Log::warning('Blocked IP attempted access', [
                'ip' => $ip,
                'user_agent' => $request->userAgent(),
                'path' => $request->path(),
            ]);

            return response()->json([
                'message' => 'Access denied due to suspicious activity. Please try again later.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
