<?php

namespace App\Http\Requests\Setting;

use App\Abstracts\Http\FormRequest;

class Currency extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Check if store or update
        if (in_array($this->getMethod(), ['PATCH', 'PUT'])) {
            $id = is_numeric($this->currency) ? $this->currency : $this->currency->getAttribute('id');
        } else {
            $id = null;
        }

        // Get company id
        $company_id = (int) $this->request->get('company_id');

        return [
            'name' => 'required|string',
            'code' => 'required|string|currency_code|unique:currencies,NULL,' . ($id ?? 'null') . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'rate' => 'required|gt:0',
            'enabled' => 'integer|boolean',
            'default_currency' => 'nullable|boolean',
            'decimal_mark' => 'nullable|string|different:thousands_separator|regex:/^[A-Za-z.,_\s-]+$/',
            'symbol_first' => 'nullable|boolean',
            'thousands_separator' => 'nullable|different:decimal_mark|regex:/^[A-Za-z.,_\s-]+$/',
        ];
    }
}
