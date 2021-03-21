<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\Document\DocumentItemTax;

/**
 * @deprecated
 * @see DocumentItemTax
 */
class BillItemTax extends DocumentItemTax
{
    public function rules()
    {
        $rules = parent::rules();

        $rules['bill_id'] = $rules['document_id'];
        $rules['bill_item_id'] = $rules['document_item_id'];

        unset($rules['document_id'], $rules['document_item_id'], $rules['type']);

        return $rules;
    }
}
