<?php

namespace MyDDD\AuthDomain\Repositories\Eloquent\Auth;

use MyDDD\AuthDomain\Models\User;
use MyDDD\AuthDomain\DataTransferObjects\Auth\{LoginData,RegisterData};
use Illuminate\Support\Facades\Hash;

class EloquentAuthenticationRepository implements AuthenticationRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(RegisterData $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
    }

    public function verifyCredentials(LoginData $data): bool
    {
        $user = $this->findByEmail($data->email);
        
        return $user && Hash::check($data->password, $user->password);
    }

    public function markEmailAsVerified(User $user): void
    {
        $user->markEmailAsVerified();
    }

    public function updatePassword(User $user, string $password): void
    {
        $user->update([
            'password' => Hash::make($password)
        ]);
    }
}
