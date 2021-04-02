<?php

namespace App\Http\Requests\Document;

use App\Abstracts\Http\FormRequest;

class DocumentItemTax extends FormRequest
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
            'type' => 'required|string',
            'document_id' => 'required|integer',
            'document_item_id' => 'required|integer',
            'tax_id' => 'required|integer',
            'name' => 'required|string',
            'amount' => 'required',
        ];
    }
}
