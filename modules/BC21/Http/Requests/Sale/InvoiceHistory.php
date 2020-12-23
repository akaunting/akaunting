<?php

namespace App\Http\Requests\Sale;

use App\Http\Requests\Document\DocumentHistory;

/**
 * @deprecated
 * @see DocumentHistory
 */
class InvoiceHistory extends DocumentHistory
{
    public function rules()
    {
        $rules = parent::rules();

        $rules['invoice_id'] = $rules['document_id'];

        unset($rules['document_id'], $rules['type']);

        return $rules;
    }
}
