<?php

namespace App\Jobs\Purchase;

use App\Abstracts\Http\FormRequest;
use App\Jobs\Document\CreateDocument;
use App\Models\Document\Document;

/**
 * @deprecated
 * @see CreateDocument
 */
class CreateBill extends CreateDocument
{
    protected $bill;

    protected $request;

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
                'type' => Document::BILL_TYPE,
                'document_number' => $this->request->get('bill_number'),
                'issued_at' => $this->request->get('billed_at'),
            ]
        );

    }
}
