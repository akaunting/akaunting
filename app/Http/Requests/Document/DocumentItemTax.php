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
        return [
            'type' => 'required|string',
            'document_id' => 'required|integer',
            'document_item_id' => 'required|integer',
            'tax_id' => 'required|integer',
            'name' => 'required|string',
            'amount' => 'required',
        ];
    }
}
