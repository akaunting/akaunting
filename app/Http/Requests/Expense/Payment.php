<?php

namespace App\Http\Requests\Expense;

use App\Http\Requests\Request;
use Date;

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
            'paid_at' => 'required|date_format:Y-m-d H:i:s',
            'amount' => 'required|amount',
            'currency_code' => 'required|string|currency',
            'currency_rate' => 'required',
            'vendor_id' => 'nullable|integer',
            'category_id' => 'required|integer',
            'payment_method' => 'required|string',
            'attachment' => 'mimes:' . setting('general.file_types') . '|between:0,' . setting('general.file_size') * 1024,
        ];
    }

    public function withValidator($validator)
    {
        if ($validator->errors()->count()) {
            $paid_at = Date::parse($this->request->get('paid_at'))->format('Y-m-d');

            $this->request->set('paid_at', $paid_at);
        }
    }
}
