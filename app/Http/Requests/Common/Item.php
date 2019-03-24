<?php

namespace App\Http\Requests\Common;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class Item extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $id = $this->item->getAttribute('id');
        } else {
            $id = null;
        }

        // Get company id
        $company_id = $this->request->get('company_id');

        return [
            'name' => 'required|string',
            'sku' => [
                'required',
                Rule::unique('items')
                    ->ignore($id, 'id')
                    ->where(function($query) use($company_id) {
                        $query->where('company_id', $company_id);
                        $query->whereNull('deleted_at');
                    })
            ],
            'sale_price' => 'required',
            'purchase_price' => 'required',
            'quantity' => 'required|integer',
            'tax_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'enabled' => 'integer|boolean',
            'picture' => 'mimes:' . setting('general.file_types') . '|between:0,' . setting('general.file_size') * 1024,
        ];
    }
}
