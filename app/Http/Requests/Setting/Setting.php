<?php

namespace App\Http\Requests\Setting;

use App\Abstracts\Http\FormRequest;
use Illuminate\Support\Str;

class Setting extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $prefix = $this->request->get('_prefix', null);

        switch ($prefix) {
            case 'company':
                $logo = 'mimes:' . config('filesystems.mimes')
                        . '|between:0,' . config('filesystems.max_size') * 1024
                        . '|dimensions:max_width=' . config('filesystems.max_width') . ',max_height=' . config('filesystems.max_height');

                $rules = [
                    'name'  => 'required|string',
                    'email' => 'required|email',
                    'logo'  => $logo,
                ];

                break;
            case 'default':
                $company_id = (int) $this->request->get('company_id', company_id());

                $rules = [
                    'account'           => 'required|string|exists:accounts,id,company_id,' . $company_id . ',deleted_at,NULL',
                    'currency'          => 'required|string|currency',
                    'locale'            => 'required|string',
                    'expense_category'  => 'required|integer|exists:categories,id,company_id,' . $company_id . ',deleted_at,NULL',
                    'income_category'   => 'required|integer|exists:categories,id,company_id,' . $company_id . ',deleted_at,NULL',
                    'payment_method'    => 'required|string|payment_method',
                    'address_format'    => 'required|string',
                ];

                break;
        }

        if ($this->request->has('number_digit')) {
            $rules['number_digit'] = 'required|integer|min:1|max:20';
        }

        if ($this->request->has('number_next')) {
            $rules['number_next'] = 'required|integer';
        }

        if ($this->request->has('color')) {
            $rules['color'] = 'required|string|colour';
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
