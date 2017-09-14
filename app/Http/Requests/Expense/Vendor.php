<?php

namespace App\Http\Requests\Expense;

use App\Http\Requests\Request;

class Vendor extends Request
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
        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $id = $this->vendor->getAttribute('id');
        } else {
            $id = null;
        }

        // Get company id
        $company_id = $this->request->get('company_id');

        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:vendors,NULL,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'currency_code' => 'required|string',
        ];
    }
}
