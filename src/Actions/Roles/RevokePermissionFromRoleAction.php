<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Roles;

use MyDDD\AuthDomain\Events\PermissionRevokedFromRole;
use MyDDD\AuthDomain\Models\Role;
use MyDDD\AuthDomain\Repositories\Interfaces\RoleRepositoryInterface;

final class RevokePermissionFromRoleAction
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository
    ) {}

    public function execute(Role $role, int $permissionId): void
    {
        $role->permissions()->detach($permissionId);
        
        event(new PermissionRevokedFromRole($role, $permissionId));
    }
}
