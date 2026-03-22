<?php

namespace App\Http\Requests\OAuth;

use App\Abstracts\Http\FormRequest;

class PersonalAccessTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'   => 'required|string|max:191',
            'scopes' => 'nullable|array',
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
            'name.required' => trans('validation.required', ['attribute' => trans('general.name')]),
            'name.max'      => trans('validation.max.string', ['attribute' => trans('general.name'), 'max' => 191]),
            'scopes.array'  => trans('validation.array', ['attribute' => trans('oauth.scopes')]),
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
            'name'   => trans('general.name'),
            'scopes' => trans('oauth.scopes'),
        ];
    }
}
