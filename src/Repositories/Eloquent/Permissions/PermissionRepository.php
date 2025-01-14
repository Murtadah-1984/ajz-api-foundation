<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Repositories\Eloquent\Permissions;

use MyDDD\AuthDomain\DataTransferObjects\Permissions\PermissionData;
use MyDDD\AuthDomain\Models\Permission;
use MyDDD\AuthDomain\Repositories\Interfaces\Permissions\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use MyDDD\AuthDomain\Exceptions\Permissions\PermissionException;
use Throwable;

final class PermissionRepository implements PermissionRepositoryInterface
{
    public function create(PermissionData $data): Permission
    {
        try {
            DB::beginTransaction();

            $permission = Permission::create([
                'key' => $data->key->toString(),
                'table_name' => $data->tableName->toString(),
                'created_by' => $data->createdBy,
            ]);

            DB::commit();

            return $permission;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new PermissionException(
                "Failed to create permission: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function update(Permission $permission, PermissionData $data): Permission
    {
        try {
            DB::beginTransaction();

            $permission->update([
                'key' => $data->key->toString(),
                'table_name' => $data->tableName->toString(),
                'updated_by' => $data->updatedBy,
            ]);

            $permission = $permission->fresh();

            if (!$permission) {
                throw new ModelNotFoundException('Permission not found after update');
            }

            DB::commit();

            $this->clearPermissionCache($permission);

            return $permission;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new PermissionException(
                "Failed to update permission: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function delete(Permission $permission, ?int $deletedBy = null): bool
    {
        try {
            DB::beginTransaction();

            $updated = $permission->update(['deleted_by' => $deletedBy]);
            if (!$updated) {
                throw new PermissionException('Failed to update deleted_by');
            }

            $deleted = $permission->delete();
            if (!$deleted) {
                throw new PermissionException('Failed to delete permission');
            }

            DB::commit();

            $this->clearPermissionCache($permission);

            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new PermissionException(
                "Failed to delete permission: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function findById(int $id): Permission
    {
        try {
            $permission = Cache::remember(
                "permissions.{$id}",
                now()->addHour(),
                fn () => Permission::findOrFail($id)
            );

            return $permission;
        } catch (ModelNotFoundException $e) {
            throw new PermissionException("Permission not found with ID: {$id}");
        } catch (Throwable $e) {
            throw new PermissionException(
                "Error retrieving permission: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function findByKey(string $key): Permission
    {
        try {
            $permission = Cache::remember(
                "permissions.key.{$key}",
                now()->addHour(),
                fn () => Permission::where('key', $key)->firstOrFail()
            );

            return $permission;
        } catch (ModelNotFoundException $e) {
            throw new PermissionException("Permission not found with key: {$key}");
        } catch (Throwable $e) {
            throw new PermissionException(
                "Error retrieving permission: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        try {
            return Permission::query()
                ->with(['creator:id,name', 'updater:id,name'])
                ->latest()
                ->paginate($perPage);
        } catch (Throwable $e) {
            throw new PermissionException(
                "Error paginating permissions: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function assignToRole(Permission $permission, int $roleId): void
    {
        try {
            DB::beginTransaction();

            $permission->roles()->attach($roleId);

            DB::commit();

            $this->clearPermissionCache($permission);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new PermissionException(
                "Failed to assign role to permission: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function syncRoles(Permission $permission, array $roleIds): void
    {
        try {
            DB::beginTransaction();

            $permission->roles()->sync($roleIds);

            DB::commit();

            $this->clearPermissionCache($permission);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new PermissionException(
                "Failed to sync roles for permission: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function getByTableName(string $tableName): Collection
    {
        try {
            return Cache::remember(
                "permissions.table.{$tableName}",
                now()->addHour(),
                fn () => Permission::where('table_name', $tableName)->get()
            );
        } catch (Throwable $e) {
            throw new PermissionException(
                "Error retrieving permissions by table name: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    private function clearPermissionCache(Permission $permission): void
    {
        try {
            Cache::forget("permissions.{$permission->id}");
            Cache::forget("permissions.key.{$permission->key}");
            Cache::forget("permissions.table.{$permission->table_name}");
        } catch (Throwable $e) {
            // Log error but don't throw exception for cache clearing
            \Log::error("Failed to clear permission cache: {$e->getMessage()}", [
                'permission_id' => $permission->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}