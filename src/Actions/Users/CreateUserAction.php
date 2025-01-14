<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions\Users;

use MyDDD\AuthDomain\DataTransferObjects\UserData;
use MyDDD\AuthDomain\Events\UserCreated;
use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\Repositories\Interfaces\UserRepositoryInterface;

final class CreateUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(UserData $userData): User
    {
        $user = $this->userRepository->create($userData);
        
        event(new UserCreated($user));
        
        return $user;
    }
}