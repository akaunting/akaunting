<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\Request;

class Setting extends Request
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
            'company_name' => 'required|string',
            'company_email' => 'required|email',
            'company_logo' => 'mimes:' . setting('general.file_types', 'pdf,jpeg,jpg,png'),
        ];
    }
}
