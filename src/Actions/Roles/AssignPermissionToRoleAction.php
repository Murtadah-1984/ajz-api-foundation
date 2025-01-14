<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Roles;

use MyDDD\AuthDomain\Events\PermissionAssignedToRole;
use MyDDD\AuthDomain\Models\Role;
use MyDDD\AuthDomain\Repositories\Interfaces\RoleRepositoryInterface;

final class AssignPermissionToRoleAction
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository
    ) {}

    public function execute(Role $role, int $permissionId): void
    {
        $this->roleRepository->assignPermission($role, $permissionId);
        
        event(new PermissionAssignedToRole($role, $permissionId));
    }
}
