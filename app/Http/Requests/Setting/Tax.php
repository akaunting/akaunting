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
        if ($this->getMethod() == 'PATCH') {
            $id = is_numeric($this->tax) ? $this->tax : $this->tax->getAttribute('id');
        } else {
            $id = null;
        }

        $company_id = (int) $this->request->get('company_id');

        $type = 'required|string';

        if (!empty($this->request->get('type')) && $this->request->get('type') == 'compound') {
            $type .= '|unique:taxes,NULL,' . $id . ',id,company_id,' . $company_id . ',type,compound,deleted_at,NULL';
        }

        return [
            'name' => 'required|string',
            'rate' => 'required|min:0|max:100',
            'type' => $type,
            'enabled' => 'integer|boolean',
        ];
    }
}
