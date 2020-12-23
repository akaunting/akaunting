<?php

namespace App\Models\Sale;

use App\Models\Document\DocumentItem;

/**
 * @deprecated
 * @see DocumentItem
 */
class InvoiceItem extends DocumentItem
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
