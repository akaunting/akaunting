<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class CustomMail extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to'            => 'required|email',
            'subject'       => 'required|string',
            'body'          => 'required|string',
            'attachments.*' => 'nullable|boolean',
        ];
    }
}
