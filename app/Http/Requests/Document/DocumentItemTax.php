<?php

namespace App\Http\Requests\Document;

use App\Abstracts\Http\FormRequest;

class DocumentItemTax extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $company_id = (int) $this->request->get('company_id', company_id());

        return [
            'type' => 'required|string',
            'document_id' => 'required|integer|exists:documents,id,company_id,' . $company_id . ',deleted_at,NULL',
            'document_item_id' => 'required|integer|exists:document_items,id,company_id,' . $company_id . ',deleted_at,NULL',
            'tax_id' => 'required|integer|exists:taxes,id,company_id,' . $company_id . ',deleted_at,NULL',
            'name' => 'required|string',
            'amount' => 'required',
        ];
    }
}
