<?php

namespace App\Http\Requests\Module;

use App\Abstracts\Http\FormRequest;

class Install extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'alias' => 'alpha_dash',
            'version' => 'nullable|regex:/^[a-z0-9.]+$/i',
            'installed' => 'nullable|regex:/^[a-z0-9.]+$/i',
            'path' => 'nullable|string',
        ];
    }
}
