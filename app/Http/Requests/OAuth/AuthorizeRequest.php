<?php

namespace App\Http\Requests\OAuth;

use App\Abstracts\Http\FormRequest;

class AuthorizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Also validates that the selected company belongs to the authenticated user.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        // If a company_id is present, verify the user has access to it
        if ($this->company_id) {
            return auth()->user()
                ->companies()
                ->where('id', $this->company_id)
                ->exists();
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'auth_token' => 'required|string',
            'company_id' => 'required|integer|exists:companies,id',
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'auth_token.required' => trans('general.invalid_token'),
            'company_id.required' => trans('oauth.company_selection_required'),
            'company_id.exists'   => trans('general.error.not_in_company'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'auth_token' => 'authorization token',
            'company_id' => trans('general.company'),
        ];
    }
}
