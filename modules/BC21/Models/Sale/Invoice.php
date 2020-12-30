<?php

namespace App\Models\Sale;

use App\Models\Document\Document;
use App\Scopes\ReplaceDeprecatedColumns;

/**
 * @deprecated
 * @see Document
 */
class Invoice extends Document
{
    protected static function booted()
    {
        static::addGlobalScope(new ReplaceDeprecatedColumns);
    }

    public function setInvoiceNumberAttribute($value)
    {
        $this->attributes['document_number'] = $value;
    }

    public function getInvoiceNumberAttribute($value)
    {
        return $this->document_number;
    }

    public function setInvoicedAtAttribute($value)
    {
        $this->attributes['issued_at'] = $value;
    }

    public function getInvoicedAtAttribute($value)
    {
        return $this->issued_at;
    }
}
