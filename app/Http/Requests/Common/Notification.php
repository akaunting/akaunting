<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class Notification extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'path' => 'required|string',
            'id' => 'required|integer',
        ];
    }
}
