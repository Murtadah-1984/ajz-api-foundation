<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Roles;

use MyDDD\AuthDomain\DataTransferObjects\RoleData;
use MyDDD\AuthDomain\Events\RoleUpdated;
use MyDDD\AuthDomain\Models\Role;
use MyDDD\AuthDomain\Repositories\Interfaces\RoleRepositoryInterface;

final class UpdateRoleAction
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository
    ) {}

    public function execute(Role $role, RoleData $roleData): Role
    {
        $role = $this->roleRepository->update($role, $roleData);
        
        event(new RoleUpdated($role));
        
        return $role;
    }
}
