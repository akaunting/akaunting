<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;

class Item extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $picture = 'nullable';

        if ($this->files->get('picture')) {
            $picture = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024 . '|dimensions:max_width=1000,max_height=1000';
        }

        return [
            'name' => 'required|string',
            'sale_price' => 'required|regex:/^(?=.*?[0-9])[0-9.,]+$/',
            'purchase_price' => 'required|regex:/^(?=.*?[0-9])[0-9.,]+$/',
            'tax_ids' => 'nullable|array',
            'category_id' => 'nullable|integer',
            'enabled' => 'integer|boolean',
            'picture' => $picture,
        ];
    }
}
