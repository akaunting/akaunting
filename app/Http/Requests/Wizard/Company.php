<?php

namespace App\Http\Requests\Wizard;

use App\Abstracts\Http\FormRequest;
use App\Traits\Modules as RemoteModules;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Support\Str;

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
            $logo = 'mimes:' . config('filesystems.mimes')
                    . '|between:0,' . config('filesystems.max_size') * 1024
                    . '|dimensions:max_width=' . config('filesystems.max_width') . ',max_height=' . config('filesystems.max_height');
        }

        $rules = [
            'logo' => $logo,
        ];

        if (! setting('apps.api_key', false) && ! empty($this->request->get('api_key'))) {
            $rules['api_key'] = 'string|check';
        }

        if (setting('apps.api_key', false) && (setting('apps.api_key', false) != $this->request->get('api_key'))) {
            $rules['api_key'] = 'string|check';
        }

        return $rules;
    }

    public function messages()
    {
        $logo_dimensions = trans('validation.custom.invalid_dimension', [
            'attribute'     => Str::lower(trans('settings.company.logo')),
            'width'         => config('filesystems.max_width'),
            'height'        => config('filesystems.max_height'),
        ]);

        return [
            'logo.dimensions' => $logo_dimensions,
        ];
    }
}
