<?php

namespace App\Http\Requests\Sale;

use App\Http\Requests\Document\DocumentItemTax;

/**
 * @deprecated
 * @see DocumentItemTax
 */
class InvoiceItemTax extends DocumentItemTax
{
    public function rules()
    {
        $rules = parent::rules();

        $rules['invoice_id'] = $rules['document_id'];
        $rules['invoice_item_id'] = $rules['document_item_id'];

        unset($rules['document_id'], $rules['document_item_id'], $rules['type']);

        return $rules;
    }
}
