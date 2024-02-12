<?php

namespace App\Http\Requests\Setting;

use App\Abstracts\Http\FormRequest;

class Category extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $types = collect(config('type.category'))->keys();

        return [
            'name' => 'required|string',
            'type' => 'required|string|in:' . $types->implode(','),
            'color' => 'required|string|colour',
        ];
    }
}
