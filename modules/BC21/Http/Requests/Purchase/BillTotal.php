<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\Document\DocumentTotal;

/**
 * @deprecated
 * @see DocumentTotal
 */
class BillTotal extends DocumentTotal
{
    public function rules()
    {
        $rules = parent::rules();

        $rules['bill_id'] = $rules['document_id'];

        unset($rules['document_id'], $rules['type']);

        return $rules;
    }
}
