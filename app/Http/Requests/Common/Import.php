<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class Import extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'import' => 'required|file|extension:' . config('excel.imports.extensions'),
        ];
    }
}
