<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class Company extends FormRequest
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
        return [
            'domain' => 'required|string',
            'company_name' => 'required|string',
            'company_email' => 'required|email',
            'company_logo' => 'mimes:' . setting('general.file_types') . '|between:0,' . setting('general.file_size') * 1024,
            'default_currency' => 'required|string',
        ];
    }
}
