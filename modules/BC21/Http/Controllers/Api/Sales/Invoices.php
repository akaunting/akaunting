<?php

namespace App\Http\Controllers\Api\Sales;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Document\Document as Request;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Document\Document;
use App\Transformers\Sale\Invoice as Transformer;

class Invoices extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $invoices = Document::invoice()->with('contact', 'histories', 'items', 'transactions')->collect(['issued_at'=> 'desc']);

        return $this->response->paginator($invoices, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        // Check if we're querying by id or number
        if (is_numeric($id)) {
            $invoice = Document::find($id);
        } else {
            $invoice = Document::where('document_number', $id)->first();
        }

        return $this->response->item($invoice, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $invoice = $this->dispatch(new CreateDocument($request));

        return $this->response->created(route('api.invoices.show', $invoice->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $invoice
     * @param  $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function update(Document $invoice, Request $request)
    {
        $invoice = $this->dispatch(new UpdateDocument($invoice, $request));

        return $this->response->item($invoice->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Document $invoice
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Document $invoice)
    {
        try {
            $this->dispatch(new DeleteDocument($invoice));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
