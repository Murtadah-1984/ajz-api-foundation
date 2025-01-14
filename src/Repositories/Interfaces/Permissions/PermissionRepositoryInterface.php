<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Repositories\Interfaces\Permissions;

use MyDDD\AuthDomain\DataTransferObjects\PermissionData;
use MyDDD\AuthDomain\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function create(PermissionData $data): Permission;
    public function update(Permission $permission, PermissionData $data): Permission;
    public function delete(Permission $permission, ?int $deletedBy = null): bool;
    public function findById(int $id): ?Permission;
    public function findByKey(string $key): ?Permission;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function assignToRole(Permission $permission, int $roleId): void;
    public function syncRoles(Permission $permission, array $roleIds): void;
    public function getByTableName(string $tableName): Collection;
}
