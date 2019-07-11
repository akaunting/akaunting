<?php

namespace App\Http\Requests\Expense;

use App\Http\Requests\Request;
use Date;

class Bill extends Request
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
            $id = $this->bill->getAttribute('id');
        } else {
            $id = null;
        }

        // Get company id
        $company_id = $this->request->get('company_id');

        return [
            'bill_number' => 'required|string|unique:bills,NULL,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'bill_status_code' => 'required|string',
            'billed_at' => 'required|date_format:Y-m-d H:i:s',
            'due_at' => 'required|date_format:Y-m-d H:i:s',
            'amount' => 'required',
            'item.*.name' => 'required|string',
            'item.*.quantity' => 'required',
            'item.*.price' => 'required|amount',
            'item.*.currency' => 'required|string|currency',
            'currency_code' => 'required|string|currency',
            'currency_rate' => 'required',
            'vendor_id' => 'required|integer',
            'vendor_name' => 'required|string',
            'category_id' => 'required|integer',
            'attachment' => 'mimes:' . setting('general.file_types') . '|between:0,' . setting('general.file_size') * 1024,
        ];
    }

    public function withValidator($validator)
    {
        if ($validator->errors()->count()) {
            // Set date
            $billed_at = Date::parse($this->request->get('billed_at'))->format('Y-m-d');
            $due_at = Date::parse($this->request->get('due_at'))->format('Y-m-d');

            $this->request->set('billed_at', $billed_at);
            $this->request->set('due_at', $due_at);
        }
    }

    public function messages()
    {
        return [
            'item.*.name.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('general.name'))]),
            'item.*.quantity.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('bills.quantity'))]),
            'item.*.price.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('bills.price'))]),
            'item.*.currency.required' => trans('validation.custom.invalid_currency'),
            'item.*.currency.string' => trans('validation.custom.invalid_currency'),
        ];
    }
}
