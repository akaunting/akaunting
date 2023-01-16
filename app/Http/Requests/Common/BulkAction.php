<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class BulkAction extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'handle' => 'required|string',
            'selected' => 'required',
        ];
    }
}
