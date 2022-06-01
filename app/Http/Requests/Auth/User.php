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

        if ($this->files->get('picture')) {
            $picture = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024 . '|dimensions:max_width=1000,max_height=1000';
        }

        $email = 'required|email:rfc,dns';

        if ($this->getMethod() == 'PATCH') {
            // Updating user
            $id = is_numeric($this->user) ? $this->user : $this->user->getAttribute('id');
            $companies = $this->user->can('read-common-companies') ? 'required' : '';
            $roles = $this->user->can('read-auth-roles') ? 'required|string' : '';

            if ($this->user->contact) {
                $email .= '|unique:contacts,NULL,'
                          . $this->user->contact->id . ',id'
                          . ',company_id,' . company_id()
                          . ',type,customer'
                          . ',deleted_at,NULL';
            }
        } else {
            // Creating user
            $id = null;
            $companies = 'required';
            $roles = 'required|string';
        }

        $email .= '|unique:users,email,' . $id . ',id,deleted_at,NULL';

        $change_password = $this->request->get('change_password') == true || $this->request->get('change_password') != null;

        $current_password = $change_password ? '|current_password' : '';
        $password = $change_password ? '|confirmed' : '';

        return [
            'name' => 'required|string',
            'email' => $email,
            'current_password' => 'required_if:change_password,true' . $current_password,
            'password' => 'required_if:change_password,true' . $password,
            'companies' => $companies,
            'roles' => $roles,
            'picture' => $picture,
        ];
    }
}
