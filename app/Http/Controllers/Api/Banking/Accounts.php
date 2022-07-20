<?php

namespace App\Http\Controllers\Api\Banking;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Account as Request;
use App\Http\Resources\Banking\Account as Resource;
use App\Jobs\Banking\CreateAccount;
use App\Jobs\Banking\DeleteAccount;
use App\Jobs\Banking\UpdateAccount;
use App\Models\Banking\Account;

class Accounts extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $accounts = Account::collect();

        return Resource::collection($accounts);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Check if we're querying by id or number
        if (is_numeric($id)) {
            $account = Account::find($id);
        } else {
            $account = Account::where('number', $id)->first();
        }

        if (! $account instanceof Account) {
            return $this->errorInternal('No query results for model [' . Account::class . '] ' . $id);
        }

        return new Resource($account);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $account = $this->dispatch(new CreateAccount($request));

        return $this->created(route('api.accounts.show', $account->id), new Resource($account));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $account
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Account $account, Request $request)
    {
        try {
            $account = $this->dispatch(new UpdateAccount($account, $request));

            return new Resource($account->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Enable the specified resource in storage.
     *
     * @param  Account  $account
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable(Account $account)
    {
        $account = $this->dispatch(new UpdateAccount($account, request()->merge(['enabled' => 1])));

        return new Resource($account->fresh());
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Account  $account
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(Account $account)
    {
        try {
            $account = $this->dispatch(new UpdateAccount($account, request()->merge(['enabled' => 0])));

            return new Resource($account->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        try {
            $this->dispatch(new DeleteAccount($account));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
