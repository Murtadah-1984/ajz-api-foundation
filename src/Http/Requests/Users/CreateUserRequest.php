<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create users');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'avatar' => ['nullable', 'string'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'settings' => ['nullable', 'array'],
        ];
    }

    public function getName(): ?string
    {
        return $this->validated('name');
    }

    public function getEmail(): ?string
    {
        return $this->validated('email');
    }

    public function getPassword(): ?string
    {
        return $this->validated('password');
    }

    public function getAvatar(): ?string
    {
        return $this->validated('avatar');
    }

    public function getRoleId(): ?int
    {
        return $this->validated('role_id');
    }

    public function getRoles(): ?array
    {
        return $this->validated('roles');
    }

    public function getSettings(): ?array
    {
        return $this->validated('settings');
    }
}

