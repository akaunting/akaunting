<?php

namespace App\Http\Requests\Document;

use App\Abstracts\Http\FormRequest;
use Illuminate\Support\Str;

class DocumentItem extends FormRequest
{
    protected $quantity_size = 10;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Str::contains($this->request->get('quantity'), ['.', ','])) {
            $this->quantity_size = 12;
        }

        return [
            'type' => 'required|string',
            'document_id' => 'required|integer',
            'name' => 'required|string',
            'quantity' => 'required|max:' . $this->quantity_size,
            'price' => 'required|amount',
            'total' => 'required',
            'tax' => 'required',
            'tax_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'quantity.max' => trans('validation.size', ['attribute' => Str::lower(trans('invoices.quantity')), 'size' => $this->quantity_size]),
        ];
    }
}
