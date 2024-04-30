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
        if (in_array($this->getMethod(), ['PATCH', 'PUT'])) {
            $model = $this->isApi() ? 'transaction' : $type;

            $id = is_numeric($this->$model) ? $this->$model : $this->{$model}->getAttribute('id');
        } else {
            $id = null;
        }

        // Get company id
        $company_id = (int) $this->request->get('company_id', company_id());

        $attachment = 'nullable';

        if ($this->files->get('attachment')) {
            $attachment = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024;
        }

        $rules = [
            'type' => 'required|string',
            'number' => 'required|string|unique:transactions,NULL,' . ($id ?? 'null') . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'account_id' => 'required|integer',
            'paid_at' => 'required|date_format:Y-m-d H:i:s',
            'amount' => 'required|amount:0',
            'currency_code' => 'required|string|currency',
            'currency_rate' => 'required|gt:0',
            'document_id' => 'nullable|integer',
            'contact_id' => 'nullable|integer',
            'category_id' => 'required|integer',
            'payment_method' => 'required|string|payment_method',
            'attachment.*' => $attachment,
            'recurring_count' => 'gte:0',
            'recurring_interval' => 'exclude_unless:recurring_frequency,custom|gt:0',
        ];

        // Is Recurring
        if ($this->request->has('recurring_frequency')) {
            // first line of the recurring rule
            if ($this->request->get('recurring_frequency') == 'custom') {
                $rules['recurring_interval'] = 'required|gte:1';
                $rules['recurring_custom_frequency'] = 'required|string|in:daily,weekly,monthly,yearly';
            }

            // second line of the recurring rule
            $rules['recurring_started_at'] = 'required|date_format:Y-m-d H:i:s';

            switch($this->request->get('recurring_limit')) {
                case 'date':
                    $rules['recurring_limit_date'] = 'required|date_format:Y-m-d H:i:s|after_or_equal:recurring_started_at';
                    break;
                case 'count':
                    $rules['recurring_limit_count'] = 'required|gte:0';
                    break;
            }
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        if ($validator->errors()->count()) {
            $paid_at = Date::parse($this->request->get('paid_at'))->format('Y-m-d');

            $this->request->set('paid_at', $paid_at);

            if ($this->request->get('recurring_started_at')) {
                $recurring_started_at = Date::parse($this->request->get('recurring_started_at'))->format('Y-m-d');

                $this->request->set('recurring_started_at', $recurring_started_at);
            }

            if ($this->request->get('recurring_limit_date')) {
                $recurring_limit_date = Date::parse($this->request->get('recurring_limit_date'))->format('Y-m-d');

                $this->request->set('recurring_limit_date', $recurring_limit_date);
            }
        }
    }
}
