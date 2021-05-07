<?php

namespace App\Http\Requests\Portal;

use App\Abstracts\Http\FormRequest;

class InvoiceShow extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->guest()) {
            return true;
        }

        // Allow admin to see signed invoice
        if (user()->can('read-sales-invoices')) {
            return true;
        }

        return $this->invoice->contact_id == user()->contact->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
