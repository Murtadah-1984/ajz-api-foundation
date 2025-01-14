<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Repositories\Interfaces\Users;

use MyDDD\AuthDomain\DataTransferObjects\UserData;
use MyDDD\AuthDomain\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function create(UserData $data): User;
    public function update(User $user, UserData $data): User;
    public function delete(User $user, ?int $deletedBy = null): bool;
    public function findById(int $id): User;
    public function findByEmail(string $email): ?User;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function syncRoles(User $user, array $roleIds): void;
    public function verifyEmail(User $user): void;
    public function approve(User $user): void;
}
