<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Services;

use MyDDD\AuthDomain\Actions\Auth\RegisterUserAction;
use MyDDD\AuthDomain\Actions\Roles\CreateRoleAction;
use MyDDD\AuthDomain\Actions\Permissions\CreatePermissionAction;
use MyDDD\AuthDomain\Contracts\AuthDomainInterface;
use MyDDD\AuthDomain\DataTransferObjects\Auth\LoginData;
use MyDDD\AuthDomain\DataTransferObjects\Auth\RegisterData;
use MyDDD\AuthDomain\DataTransferObjects\Auth\TokenData;
use MyDDD\AuthDomain\DataTransferObjects\Permissions\PermissionData;
use MyDDD\AuthDomain\DataTransferObjects\Roles\RoleData;
use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\Services\Auth\AuthenticationService;
use MyDDD\AuthDomain\Services\Auth\TokenService;

final class AuthDomainService implements AuthDomainInterface
{
    public function __construct(
        private readonly AuthenticationService $authService,
        private readonly TokenService $tokenService,
        private readonly RegisterUserAction $registerAction,
        private readonly CreateRoleAction $createRoleAction,
        private readonly CreatePermissionAction $createPermissionAction,
    ) {
    }

    public function register(RegisterData $data): User
    {
        return $this->registerAction->execute($data);
    }

    public function login(LoginData $data): TokenData
    {
        return $this->authService->login($data);
    }

    public function logout(): void
    {
        $this->authService->logout();
    }

    public function createRole(RoleData $data): void
    {
        $this->createRoleAction->execute($data);
    }

    public function createPermission(PermissionData $data): void
    {
        $this->createPermissionAction->execute($data);
    }

    public function assignRole(User $user, string $roleName): void
    {
        $user->assignRole($roleName);
    }

    public function hasPermission(User $user, string $permission): bool
    {
        return $user->hasPermission($permission);
    }

    public function user(): ?User
    {
        return $this->authService->user();
    }

    public function verifyToken(string $token): bool
    {
        return $this->tokenService->verify($token);
    }
}
