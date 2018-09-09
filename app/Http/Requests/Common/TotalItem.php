<?php

namespace App\Http\Requests\Common;

use App\Http\Requests\Request;

class TotalItem extends Request
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
            'item.*.quantity' => 'required',
            'item.*.price' => 'required|amount',
            'item.*.currency' => 'required|string|currency',
        ];
    }

    public function messages()
    {
        return [
            'item.*.quantity.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('invoices.quantity'))]),
            'item.*.price.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('invoices.price'))]),
            'item.*.currency.required' => trans('validation.custom.invalid_currency'),
            'item.*.currency.string' => trans('validation.custom.invalid_currency'),
        ];
    }
}
