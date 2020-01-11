<?php

namespace App\Http\Requests\Purchase;

use App\Abstracts\Http\FormRequest;

class BillHistory extends FormRequest
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
            'bill_id' => 'required|integer',
            'status' => 'required|string',
            'notify' => 'required|integer',
        ];
    }
}
