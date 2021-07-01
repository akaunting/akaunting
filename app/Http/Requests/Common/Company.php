<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class Company extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $logo = 'nullable';

        if ($this->files->get('logo')) {
            $logo = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024 . '|dimensions:max_width=1000,max_height=1000';
        }

        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'currency' => 'required|string',
            'domain' => 'nullable|string',
            'logo' => $logo,
        ];
    }
}
