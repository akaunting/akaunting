<?php

namespace App\Models\Sale;

use App\Models\Document\DocumentHistory;

/**
 * @deprecated
 * @see DocumentHistory
 */
class InvoiceHistory extends DocumentHistory
{
    public function getInvoiceIdAttribute($value)
    {
        return $this->document_id;
    }

    public function setInvoiceIdAttribute($value)
    {
        $this->attributes['document_id'] = $value;
    }
}
