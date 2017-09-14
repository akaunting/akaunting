<?php

namespace App\Http\Requests\Install;

use App\Http\Requests\Request;

class Database extends Request
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
            'hostname' => 'required',
            'username' => 'required',
            'database' => 'required'
        ];
    }
}
