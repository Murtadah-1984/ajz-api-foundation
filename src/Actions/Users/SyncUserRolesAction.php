<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Users;

use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Collection;

final class SyncUserRolesAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(User $user, Collection $roleIds): void
    {
        $this->userRepository->syncRoles($user, $roleIds);
    }
}