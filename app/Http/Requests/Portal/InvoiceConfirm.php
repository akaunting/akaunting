<?php

namespace App\Http\Requests\Portal;

use App\Abstracts\Http\FormRequest;

class InvoiceConfirm extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'payment_method' => 'required|string',
        ];
    }
}
