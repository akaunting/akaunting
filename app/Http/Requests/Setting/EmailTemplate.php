<?php

namespace App\Http\Requests\Setting;

use App\Abstracts\Http\FormRequest;

class EmailTemplate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject'   => 'required|string',
            'body'      => 'required|string',
        ];
    }
}
