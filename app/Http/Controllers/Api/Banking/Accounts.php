<?php

namespace App\Http\Controllers\Api\Banking;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Banking\Account as Request;
use App\Models\Banking\Account;
use App\Transformers\Banking\Account as Transformer;
use Dingo\Api\Routing\Helpers;

class Accounts extends ApiController
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $accounts = Account::collect();

        return $this->response->paginator($accounts, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Account  $account
     * @return \Dingo\Api\Http\Response
     */
    public function show(Account $account)
    {
        return $this->response->item($account, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $account = Account::create($request->all());

        return $this->response->created(url('api/accounts/'.$account->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $account
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Account $account, Request $request)
    {
        $account->update($request->all());

        return $this->response->item($account->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Account  $account
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return $this->response->noContent();
    }
}
