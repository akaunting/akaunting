<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;
use App\Models\Banking\Transaction as Model;
use App\Utilities\Date;

class Transaction extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $type = $this->request->get('type', Model::INCOME_TYPE);

        $type = config('type.transaction.' . $type . '.route.parameter');

        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $model = $this->isApi() ? 'transaction' : $type;

            $id = is_numeric($this->$model) ? $this->$model : $this->{$model}->getAttribute('id');
        } else {
            $id = null;
        }

        // Get company id
        $company_id = (int) $this->request->get('company_id');

        $attachment = 'nullable';

        if ($this->files->get('attachment')) {
            $attachment = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024;
        }

        return [
            'type' => 'required|string',
            'number' => 'required|string|unique:transactions,NULL,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'account_id' => 'required|integer',
            'paid_at' => 'required|date_format:Y-m-d H:i:s',
            'amount' => 'required|amount',
            'currency_code' => 'required|string|currency',
            'currency_rate' => 'required|gt:0',
            'document_id' => 'nullable|integer',
            'contact_id' => 'nullable|integer',
            'category_id' => 'required|integer',
            'payment_method' => 'required|string',
            'attachment.*' => $attachment,
            'recurring_count' => 'gte:0',
            'recurring_interval' => 'exclude_unless:recurring_frequency,custom|gt:0',
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
