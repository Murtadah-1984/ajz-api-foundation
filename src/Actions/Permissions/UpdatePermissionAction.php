<?php

namespace MyDDD\AuthDomain\Actions\Permissions;

use MyDDD\AuthDomain\Models\Permission;
use MyDDD\AuthDomain\Repositories\PermissionRepository;
use Illuminate\Validation\ValidationException;

class UpdatePermissionAction
{
    public function __construct(private PermissionRepository $permissionRepository)
    {
    }

    public function handle(Permission $permission, array $data): Permission
    {
        $permission->fill($data);
        if ($this->permissionRepository->isDuplicate($permission)) {
            throw ValidationException::withMessages(['name' => 'The name has already been taken.']);
        }
        $this->permissionRepository->save($permission);
        return $permission;
    }
}
