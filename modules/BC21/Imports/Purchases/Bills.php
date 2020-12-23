<?php

namespace App\Imports\Purchases;

use App\Imports\Document\Documents;
use App\Models\Document\Document;

/**
 * @deprecated
 * @see Documents
 */
class Bills extends Documents
{
    public function __construct(string $type = '')
    {
        parent::__construct(Document::BILL_TYPE);
    }
}
