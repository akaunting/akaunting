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

        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $id = is_numeric($this->user) ? $this->user : $this->user->getAttribute('id');
            $required = '';
        } else {
            $id = null;
            $required = 'required|';
        }

        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id . ',id,deleted_at,NULL',
            'password' => $required . 'confirmed',
            'companies' => 'required',
            'roles' => 'required',
            'picture' => $picture,
        ];
    }
}
