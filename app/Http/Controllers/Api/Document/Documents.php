<?php

namespace App\Http\Controllers\Api\Document;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Document\Document as Request;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Document\Document;
use App\Transformers\Document\Document as Transformer;

class Documents extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $documents = Document::with('contact', 'histories', 'items', 'transactions')->collect(['issued_at'=> 'desc']);

        return $this->response->paginator($documents, new Transformer());
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
            $document = Document::find($id);
        } else {
            $document = Document::where('document_number', $id)->first();
        }

        return $this->item($document, new Transformer());
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
        $document = $this->dispatch(new CreateDocument($request));

        return $this->response->created(route('api.documents.show', $document->id), $this->item($document, new Transformer()));
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
