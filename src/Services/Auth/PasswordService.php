<?php

namespace MyDDD\AuthDomain\Services\Auth;

use MyDDD\AuthDomain\DataTransferObjects\Auth\ResetPasswordData;
use MyDDD\AuthDomain\Events\PasswordReset;
use MyDDD\AuthDomain\Models\PasswordReset as PasswordResetModel;
use MyDDD\AuthDomain\Repositories\Interfaces\AuthenticationRepositoryInterface;
use Illuminate\Support\{Facades\Event, Str};


class PasswordService
{
    public function __construct(
        private AuthenticationRepositoryInterface $authRepository
    ) {}

    public function forgotPassword(string $email): void
    {
        $user = $this->authRepository->findByEmail($email);
        
        if ($user) {
            $token = Str::random(60);
            
            PasswordResetModel::create([
                'email' => $email,
                'token' => $token,
            ]);
            
            // Send password reset email with token
        }
    }

    public function resetPassword(ResetPasswordData $data): void
    {
        $reset = PasswordResetModel::where('token', $data->token)
            ->where('email', $data->email)
            ->first();

        if ($reset) {
            $user = $this->authRepository->findByEmail($data->email);
            
            if ($user) {
                $this->authRepository->updatePassword($user, $data->password);
                
                $reset->delete();
                
                Event::dispatch(new PasswordReset($user));
            }
        }
    }
}
