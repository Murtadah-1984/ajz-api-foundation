<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

final class SyncRoleUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update roles');
    }

    public function rules(): array
    {
        return [
            'users' => ['required', 'array'],
            'users.*' => ['required', 'integer', 'exists:users,id'],
        ];
    }

    public function getUsers(): array
    {
        return $this->validated('users');
    }
}