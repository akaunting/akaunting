<?php

namespace App\Imports\Sales;

use App\Imports\Document\Documents;
use App\Models\Document\Document;

/**
 * @deprecated
 * @see Documents
 */
class Invoices extends Documents
{
    public function __construct(string $type = '')
    {
        parent::__construct(Document::INVOICE_TYPE);
    }
}
