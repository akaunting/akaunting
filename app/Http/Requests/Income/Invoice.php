<?php

namespace App\Http\Requests\Income;

use App\Http\Requests\Request;

class Invoice extends Request
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
            $id = $this->invoice->getAttribute('id');
        } else {
            $id = null;
        }

        // Get company id
        $company_id = $this->request->get('company_id');

        return [
            'invoice_number' => 'required|string|unique:invoices,NULL,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'invoice_status_code' => 'required|string',
            'invoiced_at' => 'required|date',
            'due_at' => 'required|date',
            'amount' => 'required',
            'currency_code' => 'required|string|currency',
            'currency_rate' => 'required',
            'customer_id' => 'required|integer',
            'customer_name' => 'required|string',
            'category_id' => 'required|integer',
            'attachment' => 'mimes:' . setting('general.file_types') . '|between:0,' . setting('general.file_size') * 1024,
        ];
    }
}
