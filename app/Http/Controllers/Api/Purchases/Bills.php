<?php

namespace App\Http\Controllers\Api\Purchases;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Purchase\Bill as Request;
use App\Jobs\Purchase\CreateBill;
use App\Jobs\Purchase\DeleteBill;
use App\Jobs\Purchase\UpdateBill;
use App\Models\Purchase\Bill;
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
        $bills = Bill::with(['contact', 'items', 'transactions', 'histories'])->collect(['billed_at'=> 'desc']);

        return $this->response->paginator($bills, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Bill  $bill
     * @return \Dingo\Api\Http\Response
     */
    public function show(Bill $bill)
    {
        return $this->response->item($bill, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $bill = $this->dispatch(new CreateBill($request));

        return $this->response->created(url('api/bills/' . $bill->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $bill
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Bill $bill, Request $request)
    {
        $bill = $this->dispatch(new UpdateBill($bill, $request));

        return $this->item($bill->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Bill  $bill
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Bill $bill)
    {
        try {
            $this->dispatch(new DeleteBill($bill));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
