<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;
use Illuminate\Support\Str;

class Widget extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        // The widget edit modal always posts a "limit" field, even for widget
        // types that don't use it, as an empty string. Normalize that to null
        // so the "nullable" rule below actually exempts it from "integer".
        if ($this->limit === '') {
            $this->merge(['limit' => null]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $company_id = (int) $this->request->get('company_id', company_id());

        return [
            'dashboard_id' => 'required|integer|exists:dashboards,id,company_id,' . $company_id . ',deleted_at,NULL',
            'name' => 'required|string',
            'class' => 'required',
            'sort' => 'integer',
            'limit' => 'nullable|integer|min:1|max:50',
        ];
    }

    public function messages()
    {
        return [
            'class.required' => trans('validation.required', ['attribute' => Str::lower(trans_choice('general.types', 1))]),
        ];
    }
}
