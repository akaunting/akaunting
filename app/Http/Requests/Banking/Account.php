<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;

class Account extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $type = $this->request->get('type', 'bank');
        $opening_balance = 'required';

        if ($type == 'bank') {
            $opening_balance = '|amount:0';
        }

        return [
            'type' => 'required|string',
            'name' => 'required|string',
            'number' => 'required|string',
            'currency_code' => 'required|string|currency',
            'opening_balance' => $opening_balance,
            'enabled' => 'integer|boolean',
        ];
    }
}
