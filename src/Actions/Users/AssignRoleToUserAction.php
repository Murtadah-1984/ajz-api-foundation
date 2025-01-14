<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Users;

use MyDDD\AuthDomain\Events\RoleAssignedToUser;
use MyDDD\AuthDomain\Models\{User, Role};
use MyDDD\AuthDomain\Repositories\Interfaces\UserRepositoryInterface;

final class AssignRoleToUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(User $user, Role $role): void
    {
        $this->userRepository->assignRole($user, $role);
        
        event(new RoleAssignedToUser($user, $role));
    }
}