<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;
use App\Utilities\Reports;
use Illuminate\Validation\Rule;

class Report extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            // One of the reports the form offers, the class is created when the report is shown
            'class' => ['required', 'string', Rule::in(array_keys(Reports::getClasses()))],
        ];
    }
}
