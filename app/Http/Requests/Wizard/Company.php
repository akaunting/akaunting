<?php

namespace App\Http\Requests\Wizard;

use App\Http\Requests\Request;
use App\Traits\Modules as RemoteModules;
use Illuminate\Validation\Factory as ValidationFactory;

class Company extends Request
{
    use RemoteModules;

    public function __construct(ValidationFactory $validation)
    {
        $validation->extend(
            'check',
            function ($attribute, $value, $parameters) {
                return $this->checkToken($value);
            },
            trans('messages.error.invalid_token')
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'company_logo' => 'mimes:' . setting('general.file_types') . '|between:0,' . setting('general.file_size') * 1024,
        ];

        if (!setting('general.api_token', false) && !empty($this->request->get('api_token'))) {
            $rules['api_token'] = 'string|check';
        }

        return $rules;
    }
}
