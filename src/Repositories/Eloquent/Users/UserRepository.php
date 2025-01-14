<?php

namespace MyDDD\AuthDomain\Repositories\Eloquent\Users;

use MyDDD\AuthDomain\DataTransferObjects\Users\UserData;
use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\Repositories\Interfaces\Users\UserRepositoryInterface;
use MyDDD\AuthDomain\Exceptions\Users\UserException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UserRepository implements UserRepositoryInterface
{
    public function create(UserData $data): User
    {
        try {
            DB::beginTransaction();

            $user = new User([
                'name' => $data->name?->toString(),
                'email' => $data->email?->toString(),
                'password' => $data->password?->toString(),
                'avatar' => $data->avatar,
                'approved' => $data->approved,
                'verified' => $data->verified,
                'has_bio' => $data->hasBio,
                'settings' => $data->settings?->toArray(),
                'role_id' => $data->roleId,
                'created_by' => $data->createdBy,
            ]);

            $user->save();

            if ($data->roles) {
                $user->roles()->attach($data->roles);
            }

            DB::commit();
            
            $this->clearUserCache($user);

            return $user->load(['role', 'roles']);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UserException(
                "Failed to create user: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function update(User $user, UserData $data): User
    {
        try {
            DB::beginTransaction();

            $updateData = array_filter([
                'name' => $data->name?->toString(),
                'email' => $data->email?->toString(),
                'password' => $data->password?->toString(),
                'avatar' => $data->avatar,
                'settings' => $data->settings?->toArray(),
                'role_id' => $data->roleId,
                'updated_by' => $data->updatedBy,
            ]);

            $user->update($updateData);

            if ($data->roles !== null) {
                $user->roles()->sync($data->roles);
            }

            DB::commit();
            
            $this->clearUserCache($user);

            return $user->fresh(['role', 'roles']);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UserException(
                "Failed to update user: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function delete(User $user, ?int $deletedBy = null): bool
    {
        try {
            DB::beginTransaction();

            $user->update(['deleted_by' => $deletedBy]);
            $deleted = $user->delete();

            DB::commit();
            
            $this->clearUserCache($user);

            return $deleted;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UserException(
                "Failed to delete user: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function findById(int $id): User
    {
        try {
            return Cache::remember(
                "users.{$id}",
                now()->addHour(),
                fn () => User::with(['role', 'roles'])->findOrFail($id)
            );
        } catch (ModelNotFoundException $e) {
            throw new UserException("User not found with ID: {$id}");
        }
    }

    public function findByEmail(string $email): ?User
    {
        return Cache::remember(
            "users.email.{$email}",
            now()->addHour(),
            fn () => User::with(['role', 'roles'])->where('email', $email)->first()
        );
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->with(['role', 'roles', 'creator:id,name', 'updater:id,name'])
            ->latest()
            ->paginate($perPage);
    }

    public function syncRoles(User $user, array $roleIds): void
    {
        try {
            DB::beginTransaction();

            $user->roles()->sync($roleIds);

            DB::commit();
            
            $this->clearUserCache($user);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UserException(
                "Failed to sync roles for user: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function verifyEmail(User $user): void
    {
        try {
            DB::beginTransaction();

            $user->update([
                'email_verified_at' => now(),
                'verified' => true,
                'verified_at' => now(),
                'verification_token' => null
            ]);

            DB::commit();
            
            $this->clearUserCache($user);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UserException(
                "Failed to verify user email: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    public function approve(User $user): void
    {
        try {
            DB::beginTransaction();

            $user->update([
                'approved' => true,
                'updated_by' => auth()->id()
            ]);

            DB::commit();
            
            $this->clearUserCache($user);
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UserException(
                "Failed to approve user: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    private function clearUserCache(User $user): void
    {
        try {
            Cache::forget("users.{$user->id}");
            if ($user->email) {
                Cache::forget("users.email.{$user->email}");
            }
        } catch (Throwable $e) {
            \Log::error("Failed to clear user cache: {$e->getMessage()}", [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}