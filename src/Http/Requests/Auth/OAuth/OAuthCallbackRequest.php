<?php

declare(strict_types=1);

namespace MyDDD\AuthDomain\Infrastructure\OAuthRequests;

use MyDDD\AuthDomain\OAuth\Exceptions\OAuthException;
use App\Domains\Shared\Translation\DomainTranslationManager;
use Illuminate\Foundation\Http\FormRequest;

final class OAuthCallbackRequest extends FormRequest
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
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string'],
            'state' => ['required', 'string'],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @throws OAuthException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        throw OAuthException::missingCode();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $translator = app(DomainTranslationManager::class);
        
        return [
            'code.required' => $translator->get('Auth', 'validation.oauth.code.required'),
            'code.string' => $translator->get('Auth', 'validation.oauth.code.string'),
            'state.required' => $translator->get('Auth', 'validation.oauth.state.required'),
            'state.string' => $translator->get('Auth', 'validation.oauth.state.string'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure code and state are strings
        if ($this->has('code')) {
            $this->merge([
                'code' => (string) $this->input('code'),
            ]);
        }

        if ($this->has('state')) {
            $this->merge([
                'state' => (string) $this->input('state'),
            ]);
        }
    }
}
