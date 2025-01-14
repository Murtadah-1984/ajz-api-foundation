<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Repositories\Interfaces\Roles;

use MyDDD\AuthDomain\DataTransferObjects\RoleData;
use MyDDD\AuthDomain\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    public function create(RoleData $data): Role;
    public function update(Role $role, RoleData $data): Role;
    public function delete(Role $role, ?int $deletedBy = null): bool;
    public function findById(int $id): Role;
    public function findByName(string $name): Role;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function assignPermission(Role $role, int $permissionId): void;
    public function syncPermissions(Role $role, array $permissionIds): void;
    public function assignToUser(Role $role, int $userId): void;
    public function syncUsers(Role $role, array $userIds): void;
    public function getByPermission(int $permissionId): Collection;
}