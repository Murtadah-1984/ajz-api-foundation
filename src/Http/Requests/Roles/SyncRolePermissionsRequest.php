<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

final class SyncRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update roles');
    }

    public function rules(): array
    {
        return [
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'integer', 'exists:permissions,id'],
        ];
    }

    public function getPermissions(): array
    {
        return $this->validated('permissions');
    }
}

