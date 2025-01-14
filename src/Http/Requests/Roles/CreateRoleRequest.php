<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

final class CreateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create roles');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'display_name' => ['required', 'string', 'max:255'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }

    public function getName(): string
    {
        return $this->validated('name');
    }

    public function getDisplayName(): string
    {
        return $this->validated('display_name');
    }

    public function getPermissions(): ?array
    {
        return $this->validated('permissions');
    }
}