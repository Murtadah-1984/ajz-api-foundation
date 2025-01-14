<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Repositories\Eloquent;

use MyDDD\AuthDomain\DataTransferObjects\Roles\RoleData;
use MyDDD\AuthDomain\Models\Role;
use MyDDD\AuthDomain\Repositories\Interfaces\Roles\RoleRepositoryInterface;
use MyDDD\AuthDomain\Exceptions\Roles\RoleException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

final class RoleRepository implements RoleRepositoryInterface
{
    public function create(RoleData $data): Role
    {
        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $data->name->toString(),
                'display_name' => $data->displayName->toString(),
                'created_by' => $data->createdBy,
            ]);

            if ($data->permissions) {
                $role->permissions()->attach($data->permissions);
            }

            DB::commit();
            
            $this->clearRoleCache($role);

            return $role;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new RoleException(
                "Failed to create role: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function update(Role $role, RoleData $data): Role
    {
        try {
            DB::beginTransaction();

            $role->update([
                'name' => $data->name->toString(),
                'display_name' => $data->displayName->toString(),
                'updated_by' => $data->updatedBy,
            ]);

            if ($data->permissions !== null) {
                $role->permissions()->sync($data->permissions);
            }

            $role = $role->fresh(['permissions']);

            if (!$role) {
                throw new ModelNotFoundException('Role not found after update');
            }

            DB::commit();
            
            $this->clearRoleCache($role);

            return $role;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new RoleException(
                "Failed to update role: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function delete(Role $role, ?int $deletedBy = null): bool
    {
        try {
            DB::beginTransaction();

            $role->update(['deleted_by' => $deletedBy]);
            $deleted = $role->delete();

            DB::commit();
            
            $this->clearRoleCache($role);

            return $deleted;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new RoleException(
                "Failed to delete role: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function findById(int $id): Role
    {
        try {
            return Cache::remember(
                "roles.{$id}",
                now()->addHour(),
                fn () => Role::with(['permissions', 'creator:id,name'])->findOrFail($id)
            );
        } catch (ModelNotFoundException $e) {
            throw new RoleException("Role not found with ID: {$id}");
        }
    }

    public function findByName(string $name): Role
    {
        try {
            return Cache::remember(
                "roles.name.{$name}",
                now()->addHour(),
                fn () => Role::with(['permissions'])->where('name', $name)->firstOrFail()
            );
        } catch (ModelNotFoundException $e) {
            throw new RoleException("Role not found with name: {$name}");
        }
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Role::query()
            ->with(['creator:id,name', 'updater:id,name'])
            ->withCount('users')
            ->latest()
            ->paginate($perPage);
    }

    public function assignPermission(Role $role, int $permissionId): void
    {
        try {
            DB::beginTransaction();

            $role->permissions()->attach($permissionId);

            DB::commit();
            
            $this->clearRoleCache($role);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new RoleException(
                "Failed to assign permission to role: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function syncPermissions(Role $role, array $permissionIds): void
    {
        try {
            DB::beginTransaction();

            $role->permissions()->sync($permissionIds);

            DB::commit();
            
            $this->clearRoleCache($role);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new RoleException(
                "Failed to sync permissions for role: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function assignToUser(Role $role, int $userId): void
    {
        try {
            DB::beginTransaction();

            $role->users()->attach($userId);

            DB::commit();
            
            $this->clearRoleCache($role);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new RoleException(
                "Failed to assign role to user: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function syncUsers(Role $role, array $userIds): void
    {
        try {
            DB::beginTransaction();

            $role->users()->sync($userIds);

            DB::commit();
            
            $this->clearRoleCache($role);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new RoleException(
                "Failed to sync users for role: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function getByPermission(int $permissionId): Collection
    {
        return Cache::remember(
            "roles.permission.{$permissionId}",
            now()->addHour(),
            fn () => Role::whereHas('permissions', function ($query) use ($permissionId) {
                $query->where('permissions.id', $permissionId);
            })->get()
        );
    }

    private function clearRoleCache(Role $role): void
    {
        try {
            Cache::forget("roles.{$role->id}");
            Cache::forget("roles.name.{$role->name}");
            // Clear permission-related cache
            $role->permissions->each(function ($permission) {
                Cache::forget("roles.permission.{$permission->id}");
            });
        } catch (Throwable $e) {
            \Log::error("Failed to clear role cache: {$e->getMessage()}", [
                'role_id' => $role->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}