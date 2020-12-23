<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\Document\DocumentHistory;

/**
 * @deprecated
 * @see DocumentHistory
 */
class BillHistory extends DocumentHistory
{
    public function rules()
    {
        $rules = parent::rules();

        $rules['bill_id'] = $rules['document_id'];

        unset($rules['document_id'], $rules['type']);

        return $rules;
    }
}
