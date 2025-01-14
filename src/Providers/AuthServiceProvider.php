<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Providers;

use MyDDD\AuthDomain\Models\{Permission, Role, User};
use MyDDD\AuthDomain\Policies\{PermissionPolicy, RolePolicy, UserPolicy};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerGates();
    }

    /**
     * Register authorization gates.
     */
    private function registerGates(): void
    {
        // Super admin has all permissions
        Gate::before(function (User $user) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });

        // User management gates
        Gate::define('view-users', fn (User $user) => $user->hasPermission('users.view'));
        Gate::define('create-users', fn (User $user) => $user->hasPermission('users.create'));
        Gate::define('update-users', fn (User $user) => $user->hasPermission('users.update'));
        Gate::define('delete-users', fn (User $user) => $user->hasPermission('users.delete'));

        // Role management gates
        Gate::define('view-roles', fn (User $user) => $user->hasPermission('roles.view'));
        Gate::define('create-roles', fn (User $user) => $user->hasPermission('roles.create'));
        Gate::define('update-roles', fn (User $user) => $user->hasPermission('roles.update'));
        Gate::define('delete-roles', fn (User $user) => $user->hasPermission('roles.delete'));

        // Permission management gates
        Gate::define('view-permissions', fn (User $user) => $user->hasPermission('permissions.view'));
        Gate::define('create-permissions', fn (User $user) => $user->hasPermission('permissions.create'));
        Gate::define('update-permissions', fn (User $user) => $user->hasPermission('permissions.update'));
        Gate::define('delete-permissions', fn (User $user) => $user->hasPermission('permissions.delete'));

        // Token management gates
        Gate::define('view-tokens', fn (User $user) => $user->hasPermission('tokens.view'));
        Gate::define('create-tokens', fn (User $user) => $user->hasPermission('tokens.create'));
        Gate::define('revoke-tokens', fn (User $user) => $user->hasPermission('tokens.revoke'));

        // Session management gates
        Gate::define('manage-sessions', fn (User $user) => $user->hasPermission('sessions.manage'));
    }
}
