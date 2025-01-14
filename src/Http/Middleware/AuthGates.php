<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use MyDDD\AuthDomain\Models\Role;


final class AuthGates
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            $permissions = $user->roles_all()
                              ->pluck('permissions')->flatten()
                              ->pluck('key')->unique()->toArray();

            foreach ($permissions as $permission) {
                Gate::define($permission, function ($user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
            Gate::define('manage-2fa', fn () => $user->isEmailVerified());
            Gate::define('manage-tokens', fn () => $user->isEmailVerified());
            Gate::define('update-profile', fn () => !$user->isBanned());
            Gate::define('manage-sessions', fn () => $user->isEmailVerified());
        }

        return $next($request);
    }
}
