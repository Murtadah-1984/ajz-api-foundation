<?php

namespace MyDDD\AuthDomain\Repositories\Interfaces\Auth;

use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\DataTransferObjects\Auth\LoginData;
use MyDDD\AuthDomain\DataTransferObjects\Auth\RegisterData;

interface AuthenticationRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function create(RegisterData $data): User;
    public function verifyCredentials(LoginData $data): bool;
    public function markEmailAsVerified(User $user): void;
    public function updatePassword(User $user, string $password): void;
}
