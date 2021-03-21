<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class ItemTax extends FormRequest
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
            'item_id' => 'required|integer',
            'tax_id' => 'required|integer',
        ];
    }
}
