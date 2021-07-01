<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;

class ReconciliationCalculate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'currency_code' => 'required|string|currency',
            'closing_balance' => 'required',
            'transactions' => 'required',
        ];
    }
}
