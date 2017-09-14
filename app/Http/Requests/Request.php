<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
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
}
