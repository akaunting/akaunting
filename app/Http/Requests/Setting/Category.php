<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\Request;

class Category extends Request
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
            'name' => 'required|string',
            'type' => 'required|string',
            'color' => 'required|string',
        ];
    }
}
