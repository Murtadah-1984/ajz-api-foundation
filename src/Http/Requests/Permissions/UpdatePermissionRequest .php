<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Requests\Permissions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update permissions');
    }

    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'key')->ignore($this->permission->id),
            ],
            'table_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function getKey(): string
    {
        return $this->validated('key');
    }

    public function getTableName(): string
    {
        return $this->validated('table_name');
    }
}
