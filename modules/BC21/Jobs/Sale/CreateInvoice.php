<?php

namespace App\Jobs\Sale;

use App\Abstracts\Http\FormRequest;
use App\Jobs\Document\CreateDocument;
use App\Models\Document\Document;

/**
 * @deprecated
 * @see CreateDocument
 */
class CreateInvoice extends CreateDocument
{
    /**
     * Create a new job instance.
     *
     * @param FormRequest|array $request
     */
    public function __construct($request)
    {
        parent::__construct($request);

        $this->request->merge(
            [
                'type' => Document::INVOICE_TYPE,
                'document_number' => $this->request->get('invoice_number'),
                'issued_at' => $this->request->get('invoiced_at'),
            ]
        );

    }
}
