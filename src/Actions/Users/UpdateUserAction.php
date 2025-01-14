<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Users;

use MyDDD\AuthDomain\DataTransferObjects\UserData;
use MyDDD\AuthDomain\Events\UserUpdated;
use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\Repositories\Interfaces\UserRepositoryInterface;

final class UpdateUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(User $user, UserData $userData): User
    {
        $updatedUser = $this->userRepository->update($user, $userData);
        
        event(new UserUpdated($updatedUser));
        
        return $updatedUser;
    }
}
