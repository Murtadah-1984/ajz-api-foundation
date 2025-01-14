<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update roles');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->role->id),
            ],
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