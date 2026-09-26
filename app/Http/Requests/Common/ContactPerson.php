<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class ContactPerson extends FormRequest
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
            'contact_id' => 'required|integer|exists:contacts,id,company_id,' . $company_id . ',deleted_at,NULL',
            'name' => 'nullable|string',
            'email' => 'nullable|email:rfc,dns',
            'phone' => 'nullable|string',
        ];
    }
}
