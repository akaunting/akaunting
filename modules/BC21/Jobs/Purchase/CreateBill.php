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
     * @param FormRequest $request
     */
    public function __construct($request)
    {
        $request->merge(
            [
                'type' => Document::BILL_TYPE,
                'document_number' => $request->get('bill_number'),
                'issued_at' => $request->get('billed_at'),
            ]
        );

        parent::__construct($request);
    }
}
