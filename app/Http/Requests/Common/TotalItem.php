<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class TotalItem extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'items.*.quantity' => 'required',
            'items.*.price' => 'required|amount',
            'items.*.currency' => 'required|string|currency',
        ];
    }

    public function messages()
    {
        return [
            'items.*.quantity.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('invoices.quantity'))]),
            'items.*.price.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('invoices.price'))]),
            'items.*.currency.required' => trans('validation.custom.invalid_currency'),
            'items.*.currency.string' => trans('validation.custom.invalid_currency'),
        ];
    }
}
