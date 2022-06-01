<?php

namespace App\Events\Document;

use App\Abstracts\Event;
use App\Traits\Transactions;

class PaymentReceived extends Event
{
    use Transactions;

    public $document;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $document
     */
    public function __construct($document, $request = [])
    {
        $this->document = $document;
                
        if (empty($request['number'])) {
            $request['number'] = $this->getNextTransactionNumber();
        }

        $this->request  = $request;
    }
}
