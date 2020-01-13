<?php

namespace App\Http\Controllers\Api\Sales;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Sale\Invoice as Request;
use App\Jobs\Sale\CreateInvoice;
use App\Jobs\Sale\DeleteInvoice;
use App\Jobs\Sale\UpdateInvoice;
use App\Models\Sale\Invoice;
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
        $invoices = Invoice::with(['contact', 'items', 'transactions', 'histories'])->collect(['invoiced_at'=> 'desc']);

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
            $invoice = Invoice::find($id);
        } else {
            $invoice = Invoice::where('invoice_number', $id)->first();
        }

        return $this->response->item($invoice, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $invoice = $this->dispatch(new CreateInvoice($request));

        return $this->response->created(url('api/invoices/' . $invoice->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $invoice
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Invoice $invoice, Request $request)
    {
        $invoice = $this->dispatch(new UpdateInvoice($invoice, $request));

        return $this->response->item($invoice->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Invoice  $invoice
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        try {
            $this->dispatch(new DeleteInvoice($invoice));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
