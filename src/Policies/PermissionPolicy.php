<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Policies;

use MyDDD\AuthDomain\Models\{Permission, User};
use Illuminate\Auth\Access\HandlesAuthorization;

final class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('permissions.view');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermission('permissions.view');
    }

    public function create(User $user): bool
    {
        // Only super-admins can create new permissions
        if (!$user->hasRole('super-admin')) {
            return false;
        }

        return $user->hasPermission('permissions.create');
    }

    public function update(User $user, Permission $permission): bool
    {
        // Only super-admins can modify permissions
        if (!$user->hasRole('super-admin')) {
            return false;
        }

        // Prevent modification of critical system permissions
        if ($this->isCriticalPermission($permission)) {
            return false;
        }

        return $user->hasPermission('permissions.update');
    }

    public function delete(User $user, Permission $permission): bool
    {
        // Only super-admins can delete permissions
        if (!$user->hasRole('super-admin')) {
            return false;
        }

        // Prevent deletion of critical system permissions
        if ($this->isCriticalPermission($permission)) {
            return false;
        }

        return $user->hasPermission('permissions.delete');
    }

    public function restore(User $user, Permission $permission): bool
    {
        // Only super-admins can restore permissions
        if (!$user->hasRole('super-admin')) {
            return false;
        }

        return $user->hasPermission('permissions.update');
    }

    public function forceDelete(User $user, Permission $permission): bool
    {
        // Only super-admins can force delete permissions
        if (!$user->hasRole('super-admin')) {
            return false;
        }

        // Prevent force deletion of critical system permissions
        if ($this->isCriticalPermission($permission)) {
            return false;
        }

        return $user->hasPermission('permissions.delete');
    }

    /**
     * Determine if the given permission is critical to system operation.
     */
    private function isCriticalPermission(Permission $permission): bool
    {
        return in_array($permission->name, [
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
        ], true);
    }
}
