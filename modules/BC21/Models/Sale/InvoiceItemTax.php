<?php

namespace App\Models\Sale;

use App\Models\Document\DocumentItemTax;

/**
 * @deprecated
 * @see DocumentItemTax
 */
class InvoiceItemTax extends DocumentItemTax
{
    public function getInvoiceIdAttribute($value)
    {
        return $this->document_id;
    }

    public function setInvoiceIdAttribute($value)
    {
        $this->attributes['document_id'] = $value;
    }

    public function getInvoiceItemIdAttribute($value)
    {
        return $this->document_item_id;
    }

    public function setInvoiceItemIdAttribute($value)
    {
        $this->attributes['document_item_id'] = $value;
    }
}
