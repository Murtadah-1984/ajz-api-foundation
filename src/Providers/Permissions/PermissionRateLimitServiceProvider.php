<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Providers\Permissions;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class PermissionRateLimitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api.permissions', function (Request $request) {
            return [
                // Default API rate limit
                Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()),
                
                // Specific limits for write operations
                Limit::perMinute(30)
                    ->by($request->user()?->id ?: $request->ip())
                    ->response(function () {
                        return response()->json([
                            'message' => 'Too many requests. Please try again later.',
                            'status' => 429
                        ], 429);
                    })
                    ->when(fn () => in_array($request->method(), ['POST', 'PUT', 'PATCH'])),
                    
                // More restrictive limit for deletions
                Limit::perMinute(15)
                    ->by($request->user()?->id ?: $request->ip())
                    ->response(function () {
                        return response()->json([
                            'message' => 'Too many delete requests. Please try again later.',
                            'status' => 429
                        ], 429);
                    })
                    ->when(fn () => $request->method() === 'DELETE'),
            ];
        });
    }
}