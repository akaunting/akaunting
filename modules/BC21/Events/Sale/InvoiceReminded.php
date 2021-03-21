<?php

namespace App\Events\Sale;

use App\Events\Document\DocumentReminded;
use App\Models\Document\Document;
use App\Notifications\Sale\Invoice as Notification;

/**
 * @deprecated
 * @see DocumentReminded
 */
class InvoiceReminded extends DocumentReminded
{
    public function __construct(Document $document)
    {
        parent::__construct($document, Notification::class);
    }
}
