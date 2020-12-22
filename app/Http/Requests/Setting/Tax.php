<?php

namespace App\Http\Requests\Setting;

use App\Abstracts\Http\FormRequest;

class Tax extends FormRequest
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
            'rate' => 'required|min:0|max:100',
            'type' => 'required|string',
            'enabled' => 'integer|boolean',
        ];
    }
}
