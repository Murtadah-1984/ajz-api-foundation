<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Actions;

use MyDDD\AuthDomain\DataTransferObjects\UserData;
use MyDDD\AuthDomain\Events\UserRegistered;
use MyDDD\AuthDomain\Exceptions\RegistrationFailedException;
use MyDDD\AuthDomain\Exceptions\UserAlreadyExistsException;
use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Event;

final readonly class RegisterUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(UserData $userData): User
    {
        $this->validateUniqueUser($userData);

        $user = $this->userRepository->create($userData);

        if (!$user) {
            throw RegistrationFailedException::create();
        }

        Event::dispatch(new UserRegistered($user));

        return $user;
    }

    private function validateUniqueUser(UserData $userData): void
    {
        if ($this->userRepository->findByEmail($userData->email)) {
            throw UserAlreadyExistsException::withEmail($userData->email->toString());
        }

        if ($this->userRepository->findByPhoneNumber($userData->phoneNumber)) {
            throw UserAlreadyExistsException::withPhoneNumber($userData->phoneNumber->toString());
        }
    }
}
