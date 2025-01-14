<?php

namespace MyDDD\AuthDomain\Actions\Permissions;

use MyDDD\AuthDomain\Models\Permission;
use MyDDD\AuthDomain\DataTransferObjects\PermissionData;
use MyDDD\AuthDomain\Events\Permissions\PermissionDeleted;
use MyDDD\AuthDomain\Contracts\PermissionRepositoryInterface;


final class DeletePermissionAction
{
    public function __construct(
        private readonly PermissionRepositoryInterface $permissionRepository
    ) {}

    public function execute(Permission $permission): Permission
    {
        $this->permissionRepository->delete($permission);
        
        event(new PermissionDeleted($permission));
        
        return $permission;
    }
}