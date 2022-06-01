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
        $picture = $sale_price = $purchase_price = 'nullable';

        if ($this->files->get('picture')) {
            $picture = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024 . '|dimensions:max_width=1000,max_height=1000';
        }

        if ($this->request->get('sale_information') == 'true') {
            $sale_price = 'required';
        }

        if ($this->request->get('purchase_information') == 'true') {
            $purchase_price = 'required';
        }

        return [
            'type' => 'required|string',
            'name' => 'required|string',
            'sale_price' => $sale_price . '|regex:/^(?=.*?[0-9])[0-9.,]+$/',
            'purchase_price'=> $purchase_price . '|regex:/^(?=.*?[0-9])[0-9.,]+$/',
            'tax_ids' => 'nullable|array',
            'category_id' => 'nullable|integer',
            'enabled' => 'integer|boolean',
            'picture' => $picture,
        ];
    }
}
