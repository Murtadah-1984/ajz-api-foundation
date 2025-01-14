<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update users') || $this->user()->id === $this->route('user')->id;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $this->route('user')->id],
            'password' => ['sometimes', Password::defaults()],
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