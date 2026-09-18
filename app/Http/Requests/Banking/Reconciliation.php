<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;

class Reconciliation extends FormRequest
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
            'account_id' => 'required|integer|exists:accounts,id,company_id,' . $company_id . ',deleted_at,NULL',
            'started_at' => 'required|date_format:Y-m-d H:i:s|before_or_equal:ended_at',
            'ended_at' => 'required|date_format:Y-m-d H:i:s|after_or_equal:started_at',
            'closing_balance' => 'required',
        ];
    }
}
