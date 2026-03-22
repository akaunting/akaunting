<?php

namespace App\Http\Requests\OAuth;

use App\Abstracts\Http\FormRequest;

class ClientRegistrationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * DCR management endpoints rely on registration_access_token,
     * not standard session/user authentication.
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
            'client_name'                  => 'required|string|max:255',
            'redirect_uris'                => 'required|array|min:1',
            'redirect_uris.*'              => 'required|string|url',
            'grant_types'                  => 'sometimes|array',
            'token_endpoint_auth_method'   => 'sometimes|string|in:client_secret_post,client_secret_basic,none',
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
            'client_name.required'    => trans('validation.required', ['attribute' => trans('oauth.client_name')]),
            'redirect_uris.required'  => trans('validation.required', ['attribute' => trans('oauth.redirect_url')]),
            'redirect_uris.*.url'     => trans('validation.url', ['attribute' => trans('oauth.redirect_url')]),
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
            'client_name'   => trans('oauth.client_name'),
            'redirect_uris' => trans('oauth.redirect_url'),
        ];
    }
}
