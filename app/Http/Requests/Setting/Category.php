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

        $type = $this->request->get('type');
        $config = config('type.category.' . $type, []);
        $code_hidden = !empty($config['hide']) && in_array('code', $config['hide']);
        $code = $code_hidden ? 'nullable|string' : 'required|string';

        return [
            'name' => 'required|string',
            'code' => $code,
            'type' => 'required|string|in:' . $types->implode(','),
            'color' => 'required|string|colour',
        ];
    }
}
