<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class Report extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'class' => 'required|string',
        ];
    }
}
