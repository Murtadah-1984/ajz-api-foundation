<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Roles;

use MyDDD\AuthDomain\Events\RoleAssignedToUser;
use MyDDD\AuthDomain\Models\Role;
use MyDDD\AuthDomain\Repositories\Interfaces\RoleRepositoryInterface;

final class AssignRoleToUserAction
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository
    ) {}

    public function execute(Role $role, int $userId): void
    {
        $this->roleRepository->assignToUser($role, $userId);
        
        event(new RoleAssignedToUser($role, $userId));
    }
}
