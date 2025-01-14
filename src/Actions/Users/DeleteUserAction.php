<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Users;

use MyDDD\AuthDomain\Events\UserDeleted;
use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\Repositories\Interfaces\UserRepositoryInterface;

final class DeleteUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(User $user): bool
    {
        $deleted = $this->userRepository->delete($user);
        
        if ($deleted) {
            event(new UserDeleted($user));
        }
        
        return $deleted;
    }
}
