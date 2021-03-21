<?php

namespace App\Http\Requests\Sale;

use App\Http\Requests\Document\DocumentItem;

/**
 * @deprecated
 * @see DocumentItem
 */
class InvoiceItem extends DocumentItem
{
    public function rules()
    {
        $rules = parent::rules();

        $rules['invoice_id'] = $rules['document_id'];

        unset($rules['document_id'], $rules['type']);

        return $rules;
    }
}
