<?php

namespace App\Http\Controllers\Api\Incomes;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Income\Customer as Request;
use App\Http\Transformers\Income\Customer as Transformer;
use App\Models\Income\Customer;
use Dingo\Api\Routing\Helpers;

class Customers extends ApiController
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $customers = Customer::collect();

        return $this->response->paginator($customers, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        // Check if we're querying by id or email
        if (is_numeric($id)) {
            $customer = Customer::findOrFail($id);
        } else {
            $customer = Customer::where('email', $id)->first();
        }

        return $this->response->item($customer, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $customer = Customer::create($request->all());

        return $this->response->created(url('api/customers/'.$customer->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $customer
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Customer $customer, Request $request)
    {
        $customer->update($request->all());

        return $this->response->item($customer->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Customer  $customer
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return $this->response->noContent();
    }
}
