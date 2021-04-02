<?php

namespace App\Http\Requests\Expense;

use App\Http\Requests\Request;

class Payment extends Request
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
            'account_id' => 'required|integer',
            'paid_at' => 'required|date',
            'amount' => 'required',
            'currency_code' => 'required|string',
            'category_id' => 'required|integer',
            'payment_method' => 'required|string',
            'attachment' => 'mimes:' . setting('general.file_types') . '|between:0,' . setting('general.file_size') * 1024,
        ];
    }
}
