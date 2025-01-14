<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Roles;

use MyDDD\AuthDomain\Models\Role;
use MyDDD\AuthDomain\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Collection;

final class SyncRolePermissionsAction
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository
    ) {}

    public function execute(Role $role, Collection $permissionIds): void
    {
        $this->roleRepository->syncPermissions($role, $permissionIds);
    }
}