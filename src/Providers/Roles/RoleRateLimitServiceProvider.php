<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Providers\Roles;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class RoleRateLimitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api.roles', function (Request $request) {
            return [
                // Default API rate limit
                Limit::perMinute(60)
                    ->by($request->user()?->id ?: $request->ip()),
                
                // Write operations rate limit
                Limit::perMinute(30)
                    ->by($request->user()?->id ?: $request->ip())
                    ->response(function () {
                        return response()->json([
                            'message' => 'Too many requests. Please try again later.',
                            'error_code' => 'ROLE_RATE_LIMIT_EXCEEDED',
                            'status' => 429
                        ], 429);
                    })
                    ->when(fn () => in_array($request->method(), ['POST', 'PUT', 'PATCH'])),
                    
                // Delete operations rate limit
                Limit::perMinute(15)
                    ->by($request->user()?->id ?: $request->ip())
                    ->response(function () {
                        return response()->json([
                            'message' => 'Too many delete requests. Please try again later.',
                            'error_code' => 'ROLE_DELETE_LIMIT_EXCEEDED',
                            'status' => 429
                        ], 429);
                    })
                    ->when(fn () => $request->method() === 'DELETE'),
            ];
        });
    }
}