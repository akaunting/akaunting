<?php

namespace App\Events\Banking;

use App\Abstracts\Event;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;

class DocumentTransactionUpdating extends Event
{
    public $document;

    public $transaction;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $document
     * @param $transaction
     * @param $request
     */
    public function __construct(Document $document, Transaction $transaction, $request)
    {
        $this->document = $document;
        $this->transaction = $transaction;
        $this->request  = $request;
    }
}
