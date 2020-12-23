<?php

namespace App\Models\Purchase;

use App\Models\Document\DocumentItem;

/**
 * @deprecated
 * @see DocumentItem
 */
class BillItem extends DocumentItem
{
    public function getBillIdAttribute($value)
    {
        return $this->document_id;
    }

    public function setBillIdAttribute($value)
    {
        $this->attributes['document_id'] = $value;
    }
}
