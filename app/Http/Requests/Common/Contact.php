<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class Contact extends FormRequest
{
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

        $email = '';
        $required = '';

        $type = $this->request->get('type', 'customer');
        $company_id = $this->request->get('company_id');

        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $id = is_numeric($this->$type) ? $this->$type : $this->$type->getAttribute('id');
        } else {
            $id = null;
        }

        if (($this->request->get('create_user', 'false') === 'true') && empty($this->request->get('user_id'))) {
            $required = 'required|';
        }

        if (!empty($this->request->get('email'))) {
            $email = 'email|unique:contacts,NULL,' . $id . ',id,company_id,' . $company_id . ',type,' . $type . ',deleted_at,NULL';
        }

        return [
            'type' => 'required|string',
            'name' => 'required|string',
            'email' => $email,
            'user_id' => 'integer|nullable',
            'currency_code' => 'required|string|currency',
            'password' => $required . 'confirmed',
            'enabled' => 'integer|boolean',
        ];
    }
}
