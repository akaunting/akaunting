<?php

namespace App\Events\Purchase;

use App\Events\Document\DocumentReminded;
use App\Models\Document\Document;
use App\Notifications\Purchase\Bill as Notification;

/**
 * @deprecated
 * @see DocumentReminded
 */
class BillReminded extends DocumentReminded
{
    public function __construct(Document $document)
    {
        parent::__construct($document, Notification::class);
    }
}
