<?php

namespace App\Http\Requests\Portal;

use App\Traits\Contacts;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Profile extends FormRequest
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
        $id = user()->getAttribute('id');

        $picture = 'nullable';

        if ($this->request->get('picture', null)) {
            $picture = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024;
        }

        $email = [
            'required',
            'email',
            Rule::unique('users')
                ->ignore($id)
                ->where('deleted_at'),
        ];

        if (user()->contact) {
            $email[] = Rule::unique('contacts')
                           ->ignore(user()->contact->id)
                           ->where('company_id', company_id())
                           ->where('type', $this->getCustomerTypes())
                           ->where('deleted_at');
        }

        return [
            'name' => 'required|string',
            'email' => $email,
            'password' => 'confirmed',
            'picture' => $picture,
        ];
    }
}
