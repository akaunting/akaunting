<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class ItemTax extends FormRequest
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
            'item_id' => 'required|integer|exists:items,id,company_id,' . $company_id . ',deleted_at,NULL',
            'tax_id' => 'required|integer|exists:taxes,id,company_id,' . $company_id . ',deleted_at,NULL',
        ];
    }
}
