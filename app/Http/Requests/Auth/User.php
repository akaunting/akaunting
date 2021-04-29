<?php

namespace App\Http\Requests\Auth;

use App\Traits\Contacts;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class User extends FormRequest
{
    use Contacts;

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

        $email = ['required', 'email'];

        if ($this->getMethod() == 'PATCH') {
            // Updating user
            $id = is_numeric($this->user) ? $this->user : $this->user->getAttribute('id');
            $password = '';
            $companies = $this->user->can('read-common-companies') ? 'required' : '';
            $roles = $this->user->can('read-auth-roles') ? 'required' : '';

            if ($this->user->contact) {
                $email[] = Rule::unique('contacts')
                               ->ignore($this->user->contact->id)
                               ->where('company_id', company_id())
                               ->where('type', $this->getCustomerTypes())
                               ->where('deleted_at');
            }
        } else {
            // Creating user
            $id = null;
            $password = 'required|';
            $companies = 'required';
            $roles = 'required';
        }

        $email[] = Rule::unique('users')->ignore($id)->where('deleted_at');

        return [
            'name' => 'required|string',
            'email' => $email,
            'password' => $password . 'confirmed',
            'companies' => $companies,
            'roles' => $roles,
            'picture' => $picture,
        ];
    }
}
