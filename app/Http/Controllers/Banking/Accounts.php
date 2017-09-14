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
        $account->update($request->all());

        // Set default account
        if ($request['default_account']) {
            setting()->set('general.default_account', $account->id);
            setting()->save();
        }

        $message = trans('messages.success.updated', ['type' => trans_choice('general.accounts', 1)]);

        flash($message)->success();

        return redirect('banking/accounts');
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
        $canDelete = $account->canDelete();

        if ($canDelete === true) {
            $account->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('general.accounts', 1)]);

            flash($message)->success();
        } else {
            $text = array();

            if (isset($canDelete['bills'])) {
                $text[] = '<b>' . $canDelete['bills'] . '</b> ' . trans_choice('general.bills', ($canDelete['bills'] > 1) ? 2 : 1);
            }

            if (isset($canDelete['payments'])) {
                $text[] = '<b>' . $canDelete['payments'] . '</b> ' . trans_choice('general.payments', ($canDelete['payments'] > 1) ? 2 : 1);
            }

            if (isset($canDelete['invoices'])) {
                $text[] = '<b>' . $canDelete['invoices'] . '</b> ' . trans_choice('general.invoices', ($canDelete['invoices'] > 1) ? 2 : 1);
            }

            if (isset($canDelete['revenues'])) {
                $text[] = '<b>' . $canDelete['revenues'] . '</b> ' . trans_choice('general.revenues', ($canDelete['revenues'] > 1) ? 2 : 1);
            }

            $message = trans('messages.warning.deleted', ['type' => trans_choice('general.accounts', 1), 'text' => implode(', ', $text)]);

            flash($message)->warning();
        }

        return redirect('banking/accounts');
    }
}
