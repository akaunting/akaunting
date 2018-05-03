<?php

namespace App\Http\Controllers\Banking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banking\Account as Request;
use App\Models\Banking\Account;
use App\Models\Setting\Currency;

class Accounts extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $accounts = Account::collect();

        return view('banking.accounts.index', compact('accounts'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect('banking/accounts');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $currencies = Currency::enabled()->pluck('name', 'code');
        
        return view('banking.accounts.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $account = Account::create($request->all());

        // Set default account
        if ($request['default_account']) {
            setting()->set('general.default_account', $account->id);
            setting()->save();
        }

        $message = trans('messages.success.added', ['type' => trans_choice('general.accounts', 1)]);

        flash($message)->success();

        return redirect('banking/accounts');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Account  $account
     *
     * @return Response
     */
    public function edit(Account $account)
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        $account->default_account = ($account->id == setting('general.default_account')) ?: 1;
        
        return view('banking.accounts.edit', compact('account', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Account  $account
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Account $account, Request $request)
    {
        // Check if we can disable it
        if (!$request['enabled']) {
            if ($account->id == setting('general.default_account')) {
                $relationships[] = strtolower(trans_choice('general.companies', 1));
            }
        }

        if (empty($relationships)) {
            $account->update($request->all());

            // Set default account
            if ($request['default_account']) {
                setting()->set('general.default_account', $account->id);
                setting()->save();
            }

            $message = trans('messages.success.updated', ['type' => trans_choice('general.accounts', 1)]);

            flash($message)->success();

            return redirect('banking/accounts');
        } else {
            $message = trans('messages.warning.disabled', ['name' => $account->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();

            return redirect('banking/accounts/' . $account->id . '/edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Account  $account
     *
     * @return Response
     */
    public function destroy(Account $account)
    {
        $relationships = $this->countRelationships($account, [
            'bill_payments' => 'bills',
            'payments' => 'payments',
            'invoice_payments' => 'invoices',
            'revenues' => 'revenues',
        ]);

        if ($account->id == setting('general.default_account')) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));
        }

        if (empty($relationships)) {
            $account->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('general.accounts', 1)]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.deleted', ['name' => $account->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();
        }

        return redirect('banking/accounts');
    }
}
