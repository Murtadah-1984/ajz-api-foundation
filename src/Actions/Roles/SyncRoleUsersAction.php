<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Roles;

use MyDDD\AuthDomain\Events\RoleUsersSynced;
use MyDDD\AuthDomain\Models\Role;
use MyDDD\AuthDomain\Repositories\Interfaces\RoleRepositoryInterface;

final class SyncRoleUsersAction
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository
    ) {}

    public function execute(Role $role, array $userIds): void
    {
        $this->roleRepository->syncUsers($role, $userIds);
        
        event(new RoleUsersSynced($role, $userIds));
    }
}
