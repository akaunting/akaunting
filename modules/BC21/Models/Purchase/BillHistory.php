<?php

namespace App\Models\Purchase;

use App\Models\Document\DocumentHistory;

/**
 * @deprecated
 * @see DocumentHistory
 */
class BillHistory extends DocumentHistory
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
