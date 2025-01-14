<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'phone_number' => [
                'required',
                'string',
                'regex:/^\+?[1-9]\d{1,14}$/',
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone_number.regex' => trans('auth::messages.validation.phone_number.regex'),
            'password.uncompromised' => trans('auth::messages.validation.password.uncompromised'),
        ];
    }

    /**
     * Get validated data for user registration.
     *
     * @return array<string, string>
     */
    public function validatedData(): array
    {
        return $this->validated();
    }
}
