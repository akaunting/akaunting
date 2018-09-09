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
        $email = '';

        // Get company id
        $company_id = $this->request->get('company_id');

        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $id = $this->vendor->getAttribute('id');
        } else {
            $id = null;
        }

        if (!empty($this->request->get('email'))) {
            $email = 'email|unique:vendors,NULL,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL';
        }

        return [
            'user_id' => 'nullable|integer',
            'name' => 'required|string',
            'email' => $email,
            'currency_code' => 'required|string|currency',
            'enabled' => 'integer|boolean',
        ];
    }
}
