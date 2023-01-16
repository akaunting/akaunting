<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;
use Illuminate\Support\Str;

class Widget extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dashboard_id' => 'required|integer',
            'name' => 'required|string',
            'class' => 'required',
            'sort' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'class.required' => trans('validation.required', ['attribute' => Str::lower(trans_choice('general.types', 1))]),
        ];
    }
}
