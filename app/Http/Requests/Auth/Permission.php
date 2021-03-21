<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class Permission extends FormRequest
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
        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $id = is_numeric($this->permission) ? $this->permission : $this->permission->getAttribute('id');
        } else {
            $id = null;
        }

        return [
            'name' => 'required|string|unique:permissions,name,' . $id,
            'display_name' => 'required|string',
        ];
    }
}
