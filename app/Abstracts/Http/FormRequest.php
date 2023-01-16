<?php

namespace App\Abstracts\Http;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

abstract class FormRequest extends BaseFormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'company_id' => company_id(),
        ]);
    }
}
