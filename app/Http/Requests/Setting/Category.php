<?php

namespace App\Http\Requests\Setting;

use App\Abstracts\Http\FormRequest;

class Category extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'type' => 'required|string',
            'color' => 'required|string',
        ];
    }
}
