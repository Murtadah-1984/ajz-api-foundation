<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Roles;

use MyDDD\AuthDomain\DataTransferObjects\RoleData;
use MyDDD\AuthDomain\Events\RoleCreated;
use MyDDD\AuthDomain\Models\Role;
use MyDDD\AuthDomain\Repositories\Interfaces\RoleRepositoryInterface;

final class CreateRoleAction
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository
    ) {}

    public function execute(RoleData $roleData): Role
    {
        $role = $this->roleRepository->create($roleData);
        
        event(new RoleCreated($role));
        
        return $role;
    }
}