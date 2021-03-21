<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class User extends FormRequest
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
        $picture = 'nullable';

        if ($this->request->get('picture', null)) {
            $picture = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024;
        }

        if ($this->getMethod() == 'PATCH') {
            // Updating user
            $id = is_numeric($this->user) ? $this->user : $this->user->getAttribute('id');
            $password = '';
            $companies = $this->user->can('read-common-companies') ? 'required' : '';
            $roles = $this->user->can('read-auth-roles') ? 'required' : '';
        } else {
            // Creating user
            $id = null;
            $password = 'required|';
            $companies = 'required';
            $roles = 'required';
        }

        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id . ',id,deleted_at,NULL',
            'password' => $password . 'confirmed',
            'companies' => $companies,
            'roles' => $roles,
            'picture' => $picture,
        ];
    }
}
