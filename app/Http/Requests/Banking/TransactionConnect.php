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
        $company_id = company_id();

        return [
            'data' => 'required|array',
            'data.items' => 'required|array',
            'data.items.*.document_id' => 'required|integer|exists:documents,id,company_id,' . $company_id . ',deleted_at,NULL',
        ];
    }
}
