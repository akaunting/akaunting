<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class ContactPerson extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|string',
            'contact_id' => 'required|integer',
            'name' => 'nullable|string',
            'email' => 'nullable|email:rfc,dns',
            'phone' => 'nullable|string',
        ];
    }
}
