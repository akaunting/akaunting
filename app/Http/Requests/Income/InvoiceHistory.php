<?php

namespace App\Http\Requests\Income;

use App\Http\Requests\Request;

class InvoiceHistory extends Request
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
            'invoice_id' => 'required|integer',
            'status_code' => 'required|string',
            'notify' => 'required|integer',
        ];
    }
}
