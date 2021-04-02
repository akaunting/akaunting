<?php

namespace App\Http\Requests\Wizard;

use App\Abstracts\Http\FormRequest;
use App\Traits\Modules as RemoteModules;
use Illuminate\Validation\Factory as ValidationFactory;

class Company extends FormRequest
{
    use RemoteModules;

    public function __construct(ValidationFactory $validation)
    {
        $validation->extend(
            'check',
            function ($attribute, $value, $parameters) {
                return $this->checkToken($value);
            },
            trans('messages.error.invalid_apikey')
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
            'company_logo' => 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024,
        ];

        if (!setting('apps.api_key', false) && !empty($this->request->get('api_key'))) {
            $rules['api_key'] = 'string|check';
        }

        return $rules;
    }
}
