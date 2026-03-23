<?php

namespace App\Http\Requests\OAuth;

use App\Abstracts\Http\FormRequest;

class ScopeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('scope') ?? $this->route('id');

        $uniqueKey = 'unique:oauth_scopes,key' . ($id ? ',' . $id : '');

        return [
            'key'        => 'required|string|max:100|regex:/^[a-z0-9:_-]+$/|' . $uniqueKey,
            'name'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'group'      => 'nullable|string|max:50',
            'enabled'    => 'boolean',
            'is_default' => 'boolean',
            'sort_order' => 'nullable|integer',
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'key.regex'  => trans('oauth.scopes.key_format_error'),
            'key.unique' => trans('oauth.scopes.key_exists'),
        ];
    }
}
