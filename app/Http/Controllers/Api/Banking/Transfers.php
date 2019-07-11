<?php

namespace App\Http\Controllers\Api\Banking;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Banking\Transfer as Request;
use App\Models\Banking\Transfer;
use App\Models\Expense\Payment;
use App\Models\Income\Revenue;
use App\Transformers\Banking\Transfer as Transformer;
use Dingo\Api\Routing\Helpers;

class Transfers extends ApiController
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $transfers = Transfer::with(['payment', 'revenue'])->collect('payment.paid_at');

        return $this->response->paginator($transfers, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Transfer  $transfer
     * @return \Dingo\Api\Http\Response
     */
    public function show(Transfer $transfer)
    {
        return $this->response->item($transfer, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $transfer = Transfer::create($request->all());

        return $this->response->created(url('api/transfers/'.$transfer->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $transfer
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Transfer $transfer, Request $request)
    {
        $transfer->update($request->all());

        return $this->response->item($transfer->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transfer  $transfer
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Transfer $transfer)
    {
        $payment = Payment::findOrFail($transfer['payment_id']);
        $revenue = Revenue::findOrFail($transfer['revenue_id']);

        $transfer->delete();
        $payment->delete();
        $revenue->delete();

        return $this->response->noContent();
    }
}
