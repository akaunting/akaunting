<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\Document\DocumentItem;

/**
 * @deprecated
 * @see DocumentItem
 */
class BillItem extends DocumentItem
{
    public function rules()
    {
        $rules = parent::rules();

        $rules['bill_id'] = $rules['document_id'];

        unset($rules['document_id'], $rules['type']);

        return $rules;
    }
}
