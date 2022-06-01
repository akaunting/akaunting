<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;

class TransactionConnect extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.items' => 'required|array',
            'data.items.*.document_id' => 'required',
        ];
    }
}
