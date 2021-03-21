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
        $name = 'nullable';
        $email = 'nullable';
        $logo = 'nullable';

        if ($this->request->get('_prefix', null) == 'company') {
            $name = 'required|string';
            $email = 'required|email';
            $logo = 'mimes:' . config('filesystems.mimes', 'pdf,jpeg,jpg,png');
        }

        return [
            'name' => $name,
            'email' => $email,
            'logo' => $logo,
        ];
    }
}
