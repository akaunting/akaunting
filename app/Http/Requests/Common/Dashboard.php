<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class Dashboard extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'users' => 'required|array',
            'users.*' => 'integer|exists:user_companies,user_id,company_id,' . company_id(),
            //'enabled' => 'integer|boolean',
        ];
    }
}
