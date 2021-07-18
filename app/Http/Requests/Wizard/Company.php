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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $logo = 'nullable';

        if ($this->files->get('logo')) {
            $logo = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024 . '|dimensions:max_width=1000,max_height=1000';
        }

        $rules = [
            'logo' => $logo,
        ];

        if (!setting('apps.api_key', false) && !empty($this->request->get('api_key'))) {
            $rules['api_key'] = 'string|check';
        }

        return $rules;
    }
}
