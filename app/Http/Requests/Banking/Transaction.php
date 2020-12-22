<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;
use Date;

class Transaction extends FormRequest
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
        $attachment = 'nullable';

        if ($this->request->get('attachment', null)) {
            $attachment = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024;
        }

        return [
            'type' => 'required|string',
            'account_id' => 'required|integer',
            'paid_at' => 'required|date_format:Y-m-d H:i:s',
            'amount' => 'required|amount',
            'currency_code' => 'required|string|currency',
            'currency_rate' => 'required',
            'document_id' => 'nullable|integer',
            'contact_id' => 'nullable|integer',
            'category_id' => 'required|integer',
            'payment_method' => 'required|string',
            'attachment' => $attachment,
        ];
    }

    public function withValidator($validator)
    {
        if ($validator->errors()->count()) {
            $paid_at = Date::parse($this->request->get('paid_at'))->format('Y-m-d');

            $this->request->set('paid_at', $paid_at);
        }
    }
}
