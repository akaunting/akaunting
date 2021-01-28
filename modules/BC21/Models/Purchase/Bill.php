<?php

namespace App\Models\Purchase;

use App\Models\Document\Document;
use App\Scopes\ReplaceDeprecatedColumns;

/**
 * @deprecated
 * @see Document
 */
class Bill extends Document
{
    protected static function booted()
    {
        static::addGlobalScope(new ReplaceDeprecatedColumns);
    }

    public function getBillNumberAttribute($value)
    {
        return $this->document_number;
    }

    public function setBillNumberAttribute($value)
    {
        $this->attributes['document_number'] = $value;
    }

    public function getBilledAtAttribute($value)
    {
        return $this->issued_at;
    }

    public function setBilledAtAttribute($value)
    {
        $this->attributes['issued_at'] = $value;
    }
}
