<?php

namespace App\Http\Requests\Setting;

use App\Abstracts\Http\FormRequest;

class Setting extends FormRequest
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
            //'company_name' => 'required|string',
            //'company_email' => 'required|email',
            //'company_logo' => 'mimes:' . config('filesystems.mimes'), 'pdf,jpeg,jpg,png'),
        ];
    }
}
