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
     * @param FormRequest $request
     */
    public function __construct($request)
    {
        $request->merge(
            [
                'type' => Document::INVOICE_TYPE,
                'document_number' => $request->get('invoice_number'),
                'issued_at' => $request->get('invoiced_at'),
            ]
        );

        parent::__construct($request);
    }
}
