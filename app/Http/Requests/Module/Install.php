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
        $rules = [
            'name' => 'nullable|string',
            'alias' => 'alpha_dash',
            'version' => 'nullable|regex:/^[a-z0-9.]+$/i',
            'installed' => 'nullable|regex:/^[a-z0-9.]+$/i',
        ];

        $route_name = optional($this->route())->getName();

        if (in_array($route_name, ['apps.unzip', 'apps.copy', 'updates.unzip', 'updates.copy'], true)) {
            // Download step generates paths in temp-<md5> format.
            $rules['path'] = 'required|regex:/^temp-[a-f0-9]{32}$/';
        } else {
            // Path is not needed for download/install/finish endpoints.
            $rules['path'] = 'nullable';
        }

        return $rules;
    }
}
