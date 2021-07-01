<?php

namespace App\Http\Requests\Install;

use App\Abstracts\Http\FormRequest;

class Database extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'hostname' => 'required',
            'username' => 'required',
            'database' => 'required'
        ];
    }
}
