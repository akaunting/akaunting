<?php

namespace App\Http\Requests\Income;

use App\Http\Requests\Request;
use Date;

class InvoicePayment extends Request
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
            'payment_method' => 'required|string',
            'attachment' => 'mimes:jpeg,jpg,png,pdf',
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
