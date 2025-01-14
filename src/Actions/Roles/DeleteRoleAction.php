<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Roles;

use MyDDD\AuthDomain\Events\RoleDeleted;
use MyDDD\AuthDomain\Models\Role;
use MyDDD\AuthDomain\Repositories\Interfaces\RoleRepositoryInterface;

final class DeleteRoleAction
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository
    ) {}

    public function execute(Role $role, ?int $deletedBy = null): bool
    {
        $deleted = $this->roleRepository->delete($role, $deletedBy);
        
        if ($deleted) {
            event(new RoleDeleted($role));
        }
        
        return $deleted;
    }
}
