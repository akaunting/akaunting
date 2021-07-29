<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

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
        $required = '';
        $logo = 'nullable';

        $type = $this->request->get('type', 'customer');

        // @todo must put contact types under a specific array, see category
        if (empty(config('type.' . $type))) {
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

        if (($this->request->get('create_user', 'false') === 'true') && empty($this->request->get('user_id'))) {
            $required = 'required|';
        }

        if (!empty($this->request->get('email'))) {
            $email .= 'email|unique:contacts,NULL,'
                      . $id . ',id'
                      . ',company_id,' . $company_id
                      . ',type,' . $type
                      . ',deleted_at,NULL';

            if (isset($model) && $this->$model->user_id) {
                $email .= '|unique:users,NULL,' . $this->$model->user_id . ',id,deleted_at,NULL';
            }
        }

        if ($this->files->get('logo')) {
            $logo = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024 . '|dimensions:max_width=1000,max_height=1000';
        }

        return [
            'type' => 'required|string',
            'name' => 'required|string',
            'email' => $email,
            'user_id' => 'integer|nullable',
            'currency_code' => 'required|string|currency',
            'password' => $required . 'confirmed',
            'enabled' => 'integer|boolean',
            'logo' => $logo,
        ];
    }
}
