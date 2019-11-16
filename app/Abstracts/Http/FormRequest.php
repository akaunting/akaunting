<?php

namespace App\Abstracts\Http;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Support\Arr;

abstract class FormRequest extends BaseFormRequest
{
    /**
     * Set the company id to the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        // Get request data
        $data = $this->all();

        // Add active company id
        $data['company_id'] = session('company_id');

        // Reset the request data
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }

    /**
     * Determine if the given offset exists.
     *
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return Arr::has(
            $this->route() ? $this->all() + $this->route()->parameters() : $this->all(),
            $offset
        );
    }
}
