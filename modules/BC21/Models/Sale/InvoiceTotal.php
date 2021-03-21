<?php

namespace App\Models\Sale;

use App\Models\Document\DocumentTotal;

/**
 * @deprecated
 * @see DocumentTotal
 */
class InvoiceTotal extends DocumentTotal
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
