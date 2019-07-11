<?php

namespace App\Http\Requests\Banking;

use App\Http\Requests\Request;

class Account extends Request
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
            'number' => 'required|string',
            'currency_code' => 'required|string|currency',
            'opening_balance' => 'required',
            'enabled' => 'integer|boolean',
        ];
    }
}
