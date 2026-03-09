<?php

namespace App\Http\Requests\Setting;

use App\Abstracts\Http\FormRequest;
use App\Traits\Modules;

class Category extends FormRequest
{
    use Modules;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $types = collect(config('type.category'))->keys();

        $code = $this->moduleIsEnabled('double-entry') ? 'required|string' : 'nullable|string';

        return [
            'name' => 'required|string',
            'code' => $code,
            'type' => 'required|string|in:' . $types->implode(','),
            'color' => 'required|string|colour',
        ];
    }
}
