<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;

class Transfer extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $company_id = company_id();

        return [
            'from_account_id' => 'required|integer|exists:accounts,id,company_id,' . $company_id . ',deleted_at,NULL',
            'to_account_id' => 'required|integer|exists:accounts,id,company_id,' . $company_id . ',deleted_at,NULL',
            'amount' => 'required|amount',
            'transferred_at' => 'required|date_format:Y-m-d',
            'payment_method' => 'required|string|payment_method',
        ];
    }
}
