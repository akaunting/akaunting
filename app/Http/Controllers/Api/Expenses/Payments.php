<?php

namespace App\Http\Controllers\Api\Expenses;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Expense\Payment as Request;
use App\Models\Expense\Payment;
use App\Transformers\Expense\Payment as Transformer;
use Dingo\Api\Routing\Helpers;

class Payments extends ApiController
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $payments = Payment::with(['account', 'vendor', 'category'])->collect();

        return $this->response->paginator($payments, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Payment  $payment
     * @return \Dingo\Api\Http\Response
     */
    public function show(Payment $payment)
    {
        return $this->response->item($payment, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $payment = Payment::create($request->all());

        return $this->response->created(url('api/payments/'.$payment->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $payment
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Payment $payment, Request $request)
    {
        $payment->update($request->all());

        return $this->response->item($payment->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Payment  $payment
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return $this->response->noContent();
    }
}
