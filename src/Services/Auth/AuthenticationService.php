<?php

namespace MyDDD\AuthDomain\Services\Auth;

use MyDDD\AuthDomain\DataTransferObjects\Auth\{LoginData, RegisterData, TokenData};
use MyDDD\AuthDomain\Events\{UserLoggedIn, UserLoggedOut, UserRegistered};
use MyDDD\AuthDomain\Exceptions\InvalidCredentialsException;
use MyDDD\AuthDomain\Exceptions\UserNotVerifiedException;
use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\Repositories\Interfaces\{AuthenticationRepositoryInterface, TokenRepositoryInterface};
use Illuminate\Support\Facades\Event;

class AuthenticationService
{
    public function __construct(
        private AuthenticationRepositoryInterface $authRepository,
        private TokenRepositoryInterface $tokenRepository
    ) {}

    public function register(RegisterData $data): User
    {
        $user = $this->authRepository->create($data);
        
        Event::dispatch(new UserRegistered($user));
        
        return $user;
    }

    public function login(LoginData $data): TokenData
    {
        if (!$this->authRepository->verifyCredentials($data)) {
            throw new InvalidCredentialsException();
        }

        $user = $this->authRepository->findByEmail($data->email);

        if (!$user->hasVerifiedEmail()) {
            throw new UserNotVerifiedException();
        }

        Event::dispatch(new UserLoggedIn($user, request()->ip()));

        return $this->tokenRepository->createToken($user);
    }

    public function logout(string $token): void
    {
        $tokenData = $this->tokenRepository->findToken($token);
        if ($tokenData) {
            $this->tokenRepository->revokeToken($token);
            Event::dispatch(new UserLoggedOut($tokenData->user));
        }
    }
}
