<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Contracts;

use MyDDD\AuthDomain\DataTransferObjects\Auth\LoginData;
use MyDDD\AuthDomain\DataTransferObjects\Auth\RegisterData;
use MyDDD\AuthDomain\DataTransferObjects\Auth\TokenData;
use MyDDD\AuthDomain\DataTransferObjects\Permissions\PermissionData;
use MyDDD\AuthDomain\DataTransferObjects\Roles\RoleData;
use MyDDD\AuthDomain\Models\User;

interface AuthDomainInterface
{
    /**
     * Register a new user
     */
    public function register(RegisterData $data): User;

    /**
     * Authenticate a user and return token
     */
    public function login(LoginData $data): TokenData;

    /**
     * Logout the current user
     */
    public function logout(): void;

    /**
     * Create a new role
     */
    public function createRole(RoleData $data): void;

    /**
     * Create a new permission
     */
    public function createPermission(PermissionData $data): void;

    /**
     * Assign a role to a user
     */
    public function assignRole(User $user, string $roleName): void;

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(User $user, string $permission): bool;

    /**
     * Get the currently authenticated user
     */
    public function user(): ?User;

    /**
     * Verify if a token is valid
     */
    public function verifyToken(string $token): bool;
}
