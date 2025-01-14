<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Permissions;

use MyDDD\AuthDomain\DataTransferObjects\PermissionData;
use MyDDD\AuthDomain\Events\PermissionCreated;
use MyDDD\AuthDomain\Models\Permission;
use MyDDD\AuthDomain\Repositories\Interfaces\PermissionRepositoryInterface;

final class CreatePermissionAction
{
    public function __construct(
        private readonly PermissionRepositoryInterface $permissionRepository
    ) {}

    public function execute(PermissionData $permissionData): Permission
    {
        $permission = $this->permissionRepository->create($permissionData);
        
        event(new PermissionCreated($permission));
        
        return $permission;
    }
}