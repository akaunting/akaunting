<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;
use Illuminate\Support\Str;

class Contact extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $email = '';
        $logo = 'nullable';

        $type = $this->request->get('type', 'customer');

        if (empty(config('type.contact.' . $type))) {
            $type = null;
        }

        $company_id = (int) $this->request->get('company_id');

        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $model = $this->isApi() ? 'contact' : $type;

            $id = is_numeric($this->$model) ? $this->$model : $this->$model->getAttribute('id');
        } else {
            $id = null;
        }

        if (!empty($this->request->get('email'))) {
            $email .= 'email:rfc,dns|unique:contacts,NULL,'
                      . $id . ',id'
                      . ',company_id,' . $company_id
                      . ',type,' . $type
                      . ',deleted_at,NULL';

            if (isset($model) && $this->$model->user_id) {
                $email .= '|unique:users,NULL,' . $this->$model->user_id . ',id,deleted_at,NULL';
            }
        }

        if ($this->files->get('logo')) {
            $logo = 'mimes:' . config('filesystems.mimes')
                    . '|between:0,' . config('filesystems.max_size') * 1024
                    . '|dimensions:max_width=' . config('filesystems.max_width') . ',max_height=' . config('filesystems.max_height');
        }

        return [
            'type'          => 'required|string',
            'name'          => 'required|string',
            'email'         => $email,
            'user_id'       => 'integer|nullable',
            'currency_code' => 'required|string|currency',
            'enabled'       => 'integer|boolean',
            'logo'          => $logo,
        ];
    }

    public function messages()
    {
        $logo_dimensions = trans('validation.custom.invalid_dimension', [
            'attribute'     => Str::lower(trans_choice('general.pictures', 1)),
            'width'         => config('filesystems.max_width'),
            'height'        => config('filesystems.max_height'),
        ]);

        return [
            'logo.dimensions' => $logo_dimensions,
        ];
    }
}
