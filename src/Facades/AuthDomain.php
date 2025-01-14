<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Facades;

use Illuminate\Support\Facades\Facade;
use MyDDD\AuthDomain\Contracts\AuthDomainInterface;
use MyDDD\AuthDomain\DataTransferObjects\Auth\LoginData;
use MyDDD\AuthDomain\DataTransferObjects\Auth\RegisterData;
use MyDDD\AuthDomain\DataTransferObjects\Auth\TokenData;
use MyDDD\AuthDomain\DataTransferObjects\Permissions\PermissionData;
use MyDDD\AuthDomain\DataTransferObjects\Roles\RoleData;
use MyDDD\AuthDomain\Models\User;

/**
 * @method static User register(RegisterData $data)
 * @method static TokenData login(LoginData $data)
 * @method static void logout()
 * @method static void createRole(RoleData $data)
 * @method static void createPermission(PermissionData $data)
 * @method static void assignRole(User $user, string $roleName)
 * @method static bool hasPermission(User $user, string $permission)
 * @method static ?User user()
 * @method static bool verifyToken(string $token)
 * 
 * @see \MyDDD\AuthDomain\Services\AuthDomainService
 */
final class AuthDomain extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AuthDomainInterface::class;
    }
}
