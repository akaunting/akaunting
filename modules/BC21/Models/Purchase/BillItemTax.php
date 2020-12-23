<?php

namespace App\Models\Purchase;

use App\Models\Document\DocumentItemTax;

/**
 * @deprecated
 * @see DocumentItemTax
 */
class BillItemTax extends DocumentItemTax
{
    public function getBillIdAttribute($value)
    {
        return $this->document_id;
    }

    public function setBillIdAttribute($value)
    {
        $this->attributes['document_id'] = $value;
    }

    public function getBillItemIdAttribute($value)
    {
        return $this->document_item_id;
    }

    public function setBillItemIdAttribute($value)
    {
        $this->attributes['document_item_id'] = $value;
    }
}
