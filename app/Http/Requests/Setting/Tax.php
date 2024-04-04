<?php

namespace App\Http\Requests\Setting;

use App\Abstracts\Http\FormRequest;

class Tax extends FormRequest
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
            $id = is_numeric($this->tax) ? $this->tax : $this->tax->getAttribute('id');
            $enabled = 'integer|boolean';
        } else {
            $id = null;
            $enabled = 'nullable';
        }

        $company_id = (int) $this->request->get('company_id', company_id());

        $type = 'required|string|in:fixed,normal,inclusive,withholding,compound';

        if (!empty($this->request->get('type')) && $this->request->get('type') == 'compound') {
            $type .= '|unique:taxes,NULL,' . ($id ?? 'null') . ',id,company_id,' . $company_id . ',type,compound,deleted_at,NULL';
        }

        return [
            'name' => 'required|string',
            'rate' => 'required|numeric|min:0|max:100',
            'type' => $type,
            'enabled' => $enabled,
        ];
    }
}
