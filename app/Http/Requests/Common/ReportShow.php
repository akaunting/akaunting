<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ReportShow extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        // "If start_date and end_date is invalid, clear the values
        if ($validator->errors()->has('start_date') && $validator->errors()->has('end_date')) {
            request()->query->remove('start_date');
            request()->query->remove('end_date');

            return;
        }

        // If start_date is invalid, set it to be equal to end_date.
        if ($validator->errors()->has('start_date')) {
            request()->merge([
                'start_date'   => request('end_date'),
            ]);

            return;
        }

        // If end_date is invalid, set it to be equal to start_date.
        if ($validator->errors()->has('end_date')) {
            request()->merge([
                'end_date'   => request('start_date'),
            ]);

            return;
        }
    }
}
