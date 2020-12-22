<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class Widget extends FormRequest
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
            'dashboard_id' => 'required|integer',
            'name' => 'required|string',
            'class' => 'required',
        ];
    }
}
