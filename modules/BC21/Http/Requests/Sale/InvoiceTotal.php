<?php

namespace App\Http\Requests\Sale;

use App\Http\Requests\Document\DocumentTotal;

/**
 * @deprecated
 * @see DocumentTotal
 */
class InvoiceTotal extends DocumentTotal
{
    public function rules()
    {
        $rules = parent::rules();

        $rules['invoice_id'] = $rules['document_id'];

        unset($rules['document_id'], $rules['type']);

        return $rules;
    }
}
