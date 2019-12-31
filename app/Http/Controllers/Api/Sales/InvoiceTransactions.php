<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Requests\Banking\Transaction as Request;
use App\Jobs\Banking\CreateDocumentTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Models\Banking\Transaction;
use App\Models\Sale\Invoice;
use App\Transformers\Banking\Transaction as Transformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvoiceTransactions extends BaseController
{
    use Helpers, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @param  $invoice_id
     * @return \Dingo\Api\Http\Response
     */
    public function index($invoice_id)
    {
        $transactions = Transaction::document($invoice_id)->get();

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
        $transaction = Transaction::document($invoice_id)->find($id);

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
        $invoice = Invoice::find($invoice_id);

        $transaction = $this->dispatch(new CreateDocumentTransaction($invoice, $request));

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
        $transaction = Transaction::document($invoice_id)->find($id);

        $this->dispatch(new DeleteTransaction($transaction));

        return $this->response->noContent();
    }
}
