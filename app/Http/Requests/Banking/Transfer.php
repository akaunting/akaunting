<?php

namespace App\Http\Requests\Banking;

use App\Http\Requests\Request;

class Transfer extends Request
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
            'from_account_id' => 'required|integer',
            'to_account_id' => 'required|integer',
            'amount' => 'required',
            'transferred_at' => 'required|date',
            'payment_method' => 'required|string',
        ];
    }
}
