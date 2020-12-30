<?php

namespace App\Http\Controllers\Api\Document;

use App\Http\Requests\Banking\Transaction as Request;
use App\Jobs\Banking\CreateBankingDocumentTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Transformers\Banking\Transaction as Transformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentTransactions extends BaseController
{
    use Helpers, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @param  $document_id
     * @return \Dingo\Api\Http\Response
     */
    public function index($document_id)
    {
        $transactions = Transaction::document($document_id)->get();

        return $this->response->collection($transactions, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  $document_id
     * @param  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($document_id, $id)
    {
        $transaction = Transaction::document($document_id)->find($id);

        return $this->response->item($transaction, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $document_id
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($document_id, Request $request)
    {
        $document = Document::find($document_id);

        $transaction = $this->dispatch(new CreateBankingDocumentTransaction($document, $request));

        return $this->response->created(route('documents.transactions.show', [$document_id, $transaction->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $document_id
     * @param  $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($document_id, $id)
    {
        $transaction = Transaction::document($document_id)->find($id);

        $this->dispatch(new DeleteTransaction($transaction));

        return $this->response->noContent();
    }
}
