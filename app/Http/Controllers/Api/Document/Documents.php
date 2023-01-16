<?php

namespace App\Http\Controllers\Api\Document;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Document\Document as Request;
use App\Http\Resources\Document\Document as Resource;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Document\Document;

class Documents extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $documents = Document::with('contact', 'histories', 'items', 'transactions')->collect(['issued_at'=> 'desc']);

        return Resource::collection($documents);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Check if we're querying by id or number
        if (is_numeric($id)) {
            $document = Document::find($id);
        } else {
            $document = Document::where('document_number', $id)->first();
        }

        if (! $document instanceof Document) {
            return $this->errorInternal('No query results for model [' . Document::class . '] ' . $id);
        }

        return new Resource($document);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $document = $this->dispatch(new CreateDocument($request));

        return $this->created(route('api.documents.show', $document->id), new Resource($document));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $document
     * @param  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Document $document, Request $request)
    {
        $document = $this->dispatch(new UpdateDocument($document, $request));

        return new Resource($document->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Document $document
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        try {
            $this->dispatch(new DeleteDocument($document));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
