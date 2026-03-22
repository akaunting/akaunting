<?php

namespace App\Http\Requests\OAuth;

use App\Abstracts\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'         => 'required|string|max:191',
            'redirect'     => 'required|string',
            'confidential' => 'nullable|boolean',
        ];

        if ($this->isMethod('PATCH') || $this->isMethod('PUT')) {
            $rules['redirect'] = 'nullable|string';
        }

        return $rules;
    }

    /**
     * Get custom error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => trans('validation.required', ['attribute' => trans('oauth.client_name')]),
            'redirect.required' => trans('validation.required', ['attribute' => trans('oauth.redirect_url')]),
            'redirect.url' => trans('validation.url', ['attribute' => trans('oauth.redirect_url')]),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => trans('oauth.client_name'),
            'redirect' => trans('oauth.redirect_url'),
            'confidential' => trans('oauth.confidential_client'),
        ];
    }
}
