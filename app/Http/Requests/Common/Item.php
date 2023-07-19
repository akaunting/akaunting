<?php

namespace App\Http\Requests\Common;

use App\Abstracts\Http\FormRequest;
use Illuminate\Support\Str;

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
            $picture = 'mimes:' . config('filesystems.mimes')
                    . '|between:0,' . config('filesystems.max_size') * 1024
                    . '|dimensions:max_width=' . config('filesystems.max_width') . ',max_height=' . config('filesystems.max_height');
        }

        if ($this->request->get('sale_information') == 'true') {
            $sale_price = 'required';
        }

        if ($this->request->get('purchase_information') == 'true') {
            $purchase_price = 'required';
        }

        return [
            'type'              => 'required|string|in:product,service',
            'name'              => 'required|string',
            'sale_price'        => $sale_price . '|regex:/^(?=.*?[0-9])[0-9.,]+$/',
            'purchase_price'    => $purchase_price . '|regex:/^(?=.*?[0-9])[0-9.,]+$/',
            'tax_ids'           => 'nullable|array',
            'category_id'       => 'nullable|integer',
            'enabled'           => 'integer|boolean',
            'picture'           => $picture,
        ];
    }

    public function messages()
    {
        $picture_dimensions = trans('validation.custom.invalid_dimension', [
            'attribute'     => Str::lower(trans_choice('general.pictures', 1)),
            'width'         => config('filesystems.max_width'),
            'height'        => config('filesystems.max_height'),
        ]);

        return [
            'picture.dimensions' => $picture_dimensions,
        ];
    }
}
