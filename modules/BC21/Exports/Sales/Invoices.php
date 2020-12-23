<?php

namespace App\Exports\Sales;

use App\Exports\Document\Documents;
use App\Models\Document\Document;

/**
 * @deprecated
 * @see Document
 */
class Invoices extends Documents
{
    public function __construct($ids = null, string $type = '')
    {
        parent::__construct($ids, Document::INVOICE_TYPE);
    }
}
