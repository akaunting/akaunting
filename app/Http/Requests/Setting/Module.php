<?php

namespace App\Http\Requests\Setting;

use App\Abstracts\Http\FormRequest;

class Module extends FormRequest
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
        $rules = [];

        $module = module($this->request->get('module_alias'));

        if (!$fields = $module->get('settings')) {
            return $rules;
        }

        foreach ($fields as $field) {
            if (empty($field['rules'])) {
                continue;
            }

            $rules[$field['name']] = $field['rules'];
        }

        return $rules;
    }
}
