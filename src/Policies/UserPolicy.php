<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Policies;

use MyDDD\AuthDomain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users.view');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermission('users.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('users.create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermission('users.update');
    }

    public function delete(User $user, User $model): bool
    {
        // Prevent users from deleting themselves
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasPermission('users.delete');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasPermission('users.update');
    }

    public function forceDelete(User $user, User $model): bool
    {
        // Prevent users from force deleting themselves
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasPermission('users.delete');
    }
}
