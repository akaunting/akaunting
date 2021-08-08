<?php

namespace App\Http\Requests\Document;

use App\Abstracts\Http\FormRequest;
use Illuminate\Support\Str;

class DocumentItem extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $quantity_size = 5;

        if ((Str::substrCount($this->request->get('quantity'), '.') > 1) || (Str::substrCount($this->request->get('quantity'), ',') > 1)) {
            $quantity_size = 7;
        }

        return [
            'type' => 'required|string',
            'document_id' => 'required|integer',
            'name' => 'required|string',
            'quantity' => 'required|max:' . $quantity_size,
            'price' => 'required|amount',
            'total' => 'required',
            'tax' => 'required',
            'tax_id' => 'required',
        ];
    }
}
