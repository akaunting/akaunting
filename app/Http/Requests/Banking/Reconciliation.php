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
        return [
            'account_id' => 'required|integer',
            'started_at' => 'required|date_format:Y-m-d H:i:s|before_or_equal:ended_at',
            'ended_at' => 'required|date_format:Y-m-d H:i:s|after_or_equal:started_at',
            'closing_balance' => 'required',
        ];
    }
}
