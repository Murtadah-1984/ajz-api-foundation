<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Policies;

use MyDDD\AuthDomain\Models\{Role, User};
use Illuminate\Auth\Access\HandlesAuthorization;

final class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('roles.view');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermission('roles.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('roles.create');
    }

    public function update(User $user, Role $role): bool
    {
        // Prevent modification of super-admin role except by super-admins
        if ($role->name === 'super-admin' && !$user->hasRole('super-admin')) {
            return false;
        }

        return $user->hasPermission('roles.update');
    }

    public function delete(User $user, Role $role): bool
    {
        // Prevent deletion of super-admin role
        if ($role->name === 'super-admin') {
            return false;
        }

        return $user->hasPermission('roles.delete');
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->hasPermission('roles.update');
    }

    public function forceDelete(User $user, Role $role): bool
    {
        // Prevent force deletion of super-admin role
        if ($role->name === 'super-admin') {
            return false;
        }

        return $user->hasPermission('roles.delete');
    }

    public function assignPermissions(User $user, Role $role): bool
    {
        // Only super-admins can modify super-admin role permissions
        if ($role->name === 'super-admin' && !$user->hasRole('super-admin')) {
            return false;
        }

        return $user->hasPermission('roles.update');
    }

    public function assignUsers(User $user, Role $role): bool
    {
        // Only super-admins can assign users to super-admin role
        if ($role->name === 'super-admin' && !$user->hasRole('super-admin')) {
            return false;
        }

        return $user->hasPermission('roles.update');
    }
}
