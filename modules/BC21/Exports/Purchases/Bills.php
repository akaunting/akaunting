<?php

namespace App\Exports\Purchases;

use App\Exports\Document\Documents;
use App\Models\Document\Document;

/**
 * @deprecated
 * @see Document
 */
class Bills extends Documents
{
    public function __construct($ids = null, string $type = '')
    {
        parent::__construct($ids, Document::BILL_TYPE);
    }
}
