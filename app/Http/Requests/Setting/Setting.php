<?php

namespace App\Http\Requests\Setting;

use App\Abstracts\Http\FormRequest;

class Setting extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $prefix = $this->request->get('_prefix', null);

        switch ($prefix) {
            case 'company':
                $rules = [
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'logo' => 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024 . '|dimensions:max_width=1000,max_height=1000',
                ];

                break;
            case 'default':
                $rules = [
                    'currency' => 'required|string|currency',
                    'locale' => 'required|string',
                    'payment_method' => 'required|string',
                ];

                break;
        }

        return $rules;
    }
}
