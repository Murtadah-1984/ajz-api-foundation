<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Users;

use MyDDD\AuthDomain\Events\RoleRevokedFromUser;
use MyDDD\AuthDomain\Models\{User, Role};
use MyDDD\AuthDomain\Repositories\Interfaces\UserRepositoryInterface;

final class RevokeRoleFromUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(User $user, Role $role): void
    {
        $this->userRepository->revokeRole($user, $role);
        
        event(new RoleRevokedFromUser($user, $role));
    }
}
