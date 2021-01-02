<?php

namespace App\Http\Controllers\Api\Sales;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Transaction as Request;
use App\Jobs\Banking\CreateBankingDocumentTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Transformers\Banking\Transaction as Transformer;

class InvoiceTransactions extends ApiController
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-banking-transactions')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-banking-transactions')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-banking-transactions')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-banking-transactions')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  $invoice_id
     * @return \Dingo\Api\Http\Response
     */
    public function index($invoice_id)
    {
        $transactions = Transaction::documentId($invoice_id)->get();

        return $this->response->collection($transactions, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  $invoice_id
     * @param  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($invoice_id, $id)
    {
        $transaction = Transaction::documentId($invoice_id)->find($id);

        return $this->response->item($transaction, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $invoice_id
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($invoice_id, Request $request)
    {
        $invoice = Document::find($invoice_id);

        $transaction = $this->dispatch(new CreateBankingDocumentTransaction($invoice, $request));

        return $this->response->created(url('api/invoices/' . $invoice_id . '/transactions/' . $transaction->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $invoice_id
     * @param  $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($invoice_id, $id)
    {
        $transaction = Transaction::documentId($invoice_id)->find($id);

        $this->dispatch(new DeleteTransaction($transaction));

        return $this->response->noContent();
    }
}
