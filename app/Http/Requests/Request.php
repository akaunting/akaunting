<?php

namespace App\Http\Requests;

use App\Models\Setting\Currency;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;

class Request extends FormRequest
{

    private $currency_code;

    public function __construct(ValidationFactory $validation)
    {
        $validation->extend(
            'currency',
            function ($attribute, $currency_code, $parameters) {
                $currency = false;
                $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

                if (array_key_exists($currency_code, $currencies)) {
                    $currency = true;
                }

                $this->currency_code = $currency_code;

                return $currency;
            },
            trans('validation.custom.invalid_currency', ['attribute' => $this->currency_code])
        );
    }

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
