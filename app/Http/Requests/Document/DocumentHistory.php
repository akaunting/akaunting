<?php

namespace App\Http\Requests\Document;

use App\Abstracts\Http\FormRequest;

class DocumentHistory extends FormRequest
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
            'type' => 'required|string',
            'document_id' => 'required|integer|exists:documents,id,company_id,' . $company_id . ',deleted_at,NULL',
            'status' => 'required|string',
            'notify' => 'required|integer',
        ];
    }
}
