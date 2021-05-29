<?php

namespace App\Http\Controllers\Api\Banking;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Transaction as Request;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Jobs\Banking\UpdateTransaction;
use App\Models\Banking\Transaction;
use App\Transformers\Banking\Transaction as Transformer;

class Transactions extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::with('account', 'category', 'contact')->collect(['paid_at'=> 'desc']);

        return $this->response->paginator($transactions, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Transaction  $transaction
     * @return \Dingo\Api\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return $this->item($transaction, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $transaction = $this->dispatch(new CreateTransaction($request));

        return $this->response->created(route('api.transactions.show', $transaction->id), $this->item($transaction, new Transformer()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $transaction
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Transaction $transaction, Request $request)
    {
        $transaction = $this->dispatch(new UpdateTransaction($transaction, $request));

        return $this->item($transaction->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transaction  $transaction
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        try {
            $this->dispatch(new DeleteTransaction($transaction));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
