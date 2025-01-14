<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Repositories\Interfaces;

use MyDDD\AuthDomain\DataTransferObjects\UserData;
use MyDDD\AuthDomain\Models\{User, Role};
use MyDDD\AuthDomain\ValueObjects\Email;
use MyDDD\AuthDomain\ValueObjects\PhoneNumber;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function create(UserData $userData): User;
    
    public function update(User $user, UserData $userData): User;
    
    public function findByEmail(Email $email): ?User;
    
    public function findByPhoneNumber(PhoneNumber $phoneNumber): ?User;
    
    public function findByEmailVerificationToken(string $token): ?User;
    
    public function verifyEmail(User $user): bool;
    
    public function updateOtp(User $user, string $otpCode): bool;
    
    public function verifyOtp(User $user, string $otpCode): bool;
    
    public function updatePassword(User $user, string $hashedPassword): bool;
    
    public function delete(User $user): bool;
    
    public function assignRole(User $user, Role $role): void;
    
    public function revokeRole(User $user, Role $role): void;
    
    public function syncRoles(User $user, Collection $roleIds): void;
}
