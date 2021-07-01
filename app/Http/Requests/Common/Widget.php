<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class Widget extends FormRequest
{
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
            'sort' => 'integer',
        ];
    }
}
