<?php

namespace App\Models\Purchase;

use App\Models\Document\DocumentTotal;

/**
 * @deprecated
 * @see DocumentTotal
 */
class BillTotal extends DocumentTotal
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
