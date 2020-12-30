<?php

namespace App\Http\Controllers\Api\Purchases;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Document\Document as Request;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Document\Document;
use App\Transformers\Purchase\Bill as Transformer;

class Bills extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $bills = Document::bill()->with('contact', 'histories', 'items', 'transactions')->collect(['issued_at'=> 'desc']);

        return $this->response->paginator($bills, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Document $document
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show(Document $document)
    {
        return $this->response->item($document, new Transformer());
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
        $bill = $this->dispatch(new CreateDocument($request));

        return $this->response->created(route('api.bills.show', $bill->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $document
     * @param  $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function update(Document $document, Request $request)
    {
        $document = $this->dispatch(new UpdateDocument($document, $request));

        return $this->item($document->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Document $document
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Document $document)
    {
        try {
            $this->dispatch(new DeleteDocument($document));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
