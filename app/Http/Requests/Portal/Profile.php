<?php

namespace App\Http\Requests\Portal;

use Illuminate\Foundation\Http\FormRequest;

class Profile extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = user()->getAttribute('id');

        $picture = 'nullable';

        if ($this->files->get('picture')) {
            $picture = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024 . '|dimensions:max_width=1000,max_height=1000';
        }

        $email = 'required|email|unique:users,email,' . $id . ',id,deleted_at,NULL';

        if (user()->contact) {
            $email .= '|unique:contacts,NULL,'
                      . user()->contact->id . ',id'
                      . ',company_id,' . company_id()
                      . ',type,customer'
                      . ',deleted_at,NULL';
        }

        return [
            'name' => 'required|string',
            'email' => $email,
            'password' => 'confirmed',
            'picture' => $picture,
        ];
    }
}
