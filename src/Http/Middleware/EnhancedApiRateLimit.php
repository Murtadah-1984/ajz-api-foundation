<?php

declare(strict_types=1);

namespace Ajz\ApiBase\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
use Illuminate\Cache\RateLimiter;
use Ajz\ApiBase\Services\Base\RateLimitService;
use Symfony\Component\HttpFoundation\Response;

final class EnhancedApiRateLimit
{
    public function __construct(
        private readonly RateLimitService $rateLimitService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $type
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $type = 'default'): Response
    {
        $limit = $this->rateLimitService->resolveLimit($request, $type);

        if ($limit->exceeded()) {
            return new JsonResponse([
                'message' => 'Rate limit exceeded',
                'retry_after' => $limit->retryAfter
            ], 429);
        }

        /** @var Response $response */
        $response = $next($request);

        return $response
            ->header('X-RateLimit-Limit', $limit->max)
            ->header('X-RateLimit-Remaining', $limit->remaining)
            ->header('X-RateLimit-Reset', $limit->resetsAt);
    }
}
