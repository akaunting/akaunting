<?php

namespace App\Http\Requests\OAuth;

use App\Abstracts\Http\FormRequest;

class TokenIntrospectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Token introspection (RFC 7662) is authenticated via OAuth middleware,
     * not by a logged-in session user.
     *
     * @return bool
     */
    public function authorize(): bool
    {
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
            'token'           => 'required|string',
            'token_type_hint' => 'sometimes|string|in:access_token,refresh_token',
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
            'token.required' => trans('validation.required', ['attribute' => trans('oauth.token')]),
            'token.string'   => trans('validation.string', ['attribute' => trans('oauth.token')]),
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
            'token'           => trans('oauth.token'),
            'token_type_hint' => 'token_type_hint',
        ];
    }
}
