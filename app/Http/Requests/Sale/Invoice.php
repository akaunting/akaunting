<?php

namespace App\Http\Requests\Sale;

use App\Abstracts\Http\FormRequest;
use Date;

class Invoice extends FormRequest
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
            $id = is_numeric($this->invoice) ? $this->invoice : $this->invoice->getAttribute('id');
        } else {
            $id = null;
        }

        $attachment = 'nullable';

        if ($this->request->get('attachment', null)) {
            $attachment = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024;
        }

        // Get company id
        $company_id = $this->request->get('company_id');

        return [
            'invoice_number' => 'required|string|unique:invoices,NULL,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'status' => 'required|string',
            'invoiced_at' => 'required|date_format:Y-m-d H:i:s',
            'due_at' => 'required|date_format:Y-m-d H:i:s',
            'amount' => 'required',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required',
            'items.*.price' => 'required|amount',
            'items.*.currency' => 'required|string|currency',
            'currency_code' => 'required|string|currency',
            'currency_rate' => 'required',
            'contact_id' => 'required|integer',
            'contact_name' => 'required|string',
            'category_id' => 'required|integer',
            'attachment' => $attachment,
        ];
    }

    public function withValidator($validator)
    {
        if ($validator->errors()->count()) {
            // Set date
            $invoiced_at = Date::parse($this->request->get('invoiced_at'))->format('Y-m-d');
            $due_at = Date::parse($this->request->get('due_at'))->format('Y-m-d');

            $this->request->set('invoiced_at', $invoiced_at);
            $this->request->set('due_at', $due_at);
        }
    }

    public function messages()
    {
        return [
            'items.*.name.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('general.name'))]),
            'items.*.quantity.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('invoices.quantity'))]),
            'items.*.price.required' => trans('validation.required', ['attribute' => mb_strtolower(trans('invoices.price'))]),
            'items.*.currency.required' => trans('validation.custom.invalid_currency'),
            'items.*.currency.string' => trans('validation.custom.invalid_currency'),
        ];
    }
}
