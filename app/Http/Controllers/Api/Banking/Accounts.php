<?php

namespace App\Http\Controllers\Api\Banking;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Account as Request;
use App\Jobs\Banking\CreateAccount;
use App\Jobs\Banking\DeleteAccount;
use App\Jobs\Banking\UpdateAccount;
use App\Models\Banking\Account;
use App\Transformers\Banking\Account as Transformer;

class Accounts extends ApiController
{
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
     * @param  $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        // Check if we're querying by id or number
        if (is_numeric($id)) {
            $account = Account::find($id);
        } else {
            $account = Account::where('number', $id)->first();
        }

        return $this->item($account, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $account = $this->dispatch(new CreateAccount($request));

        return $this->response->created(route('api.accounts.show', $account->id), $this->item($account, new Transformer()));
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
        try {
            $account = $this->dispatch(new UpdateAccount($account, $request));

            return $this->item($account->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Account  $account
     * @return \Dingo\Api\Http\Response
     */
    public function enable(Account $account)
    {
        $account = $this->dispatch(new UpdateAccount($account, request()->merge(['enabled' => 1])));

        return $this->item($account->fresh(), new Transformer());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Account  $account
     * @return \Dingo\Api\Http\Response
     */
    public function disable(Account $account)
    {
        try {
            $account = $this->dispatch(new UpdateAccount($account, request()->merge(['enabled' => 0])));

            return $this->item($account->fresh(), new Transformer());
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Account  $account
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Account $account)
    {
        try {
            $this->dispatch(new DeleteAccount($account));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
