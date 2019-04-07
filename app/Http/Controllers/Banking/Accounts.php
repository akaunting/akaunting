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

        $currency = Currency::where('code', '=', setting('general.default_currency', 'USD'))->first();

        return view('banking.accounts.create', compact('currencies', 'currency'));
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

        $account->default_account = ($account->id == setting('general.default_account')) ? 1 : 0;

        $currency = Currency::where('code', '=', $account->currency_code)->first();

        return view('banking.accounts.edit', compact('account', 'currencies', 'currency'));
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
        // Check if we can disable or change the code
        if (!$request['enabled'] || ($account->currency_code != $request['currency_code'])) {
            $relationships = $this->countRelationships($account, [
                'invoice_payments' => 'invoices',
                'revenues' => 'revenues',
                'bill_payments' => 'bills',
                'payments' => 'payments',
            ]);

            if (!$request['enabled'] && $account->id == setting('general.default_account')) {
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
            $message = trans('messages.warning.disable_code', ['name' => $account->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();

            return redirect('banking/accounts/' . $account->id . '/edit');
        }
    }

    /**
     * Enable the specified resource.
     *
     * @param  Account  $account
     *
     * @return Response
     */
    public function enable(Account $account)
    {
        $account->enabled = 1;
        $account->save();

        $message = trans('messages.success.enabled', ['type' => trans_choice('general.accounts', 1)]);

        flash($message)->success();

        return redirect()->route('accounts.index');
    }

    /**
     * Disable the specified resource.
     *
     * @param  Account  $account
     *
     * @return Response
     */
    public function disable(Account $account)
    {
        if ($account->id == setting('general.default_account')) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));
        }

        if (empty($relationships)) {
            $account->enabled = 0;
            $account->save();

            $message = trans('messages.success.disabled', ['type' => trans_choice('general.accounts', 1)]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.disabled', ['name' => $account->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();
        }

        return redirect()->route('accounts.index');
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

    public function currency()
    {
        $account_id = (int) request('account_id');

        if (empty($account_id)) {
            return response()->json([]);
        }

        $account = Account::find($account_id);

        if (empty($account)) {
            return response()->json([]);
        }

        $currency_code = setting('general.default_currency');

        if (isset($account->currency_code)) {
            $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

            if (array_key_exists($account->currency_code, $currencies)) {
                $currency_code = $account->currency_code;
            }
        }

        // Get currency object
        $currency = Currency::where('code', $currency_code)->first();

        $account->currency_name = $currency->name;
        $account->currency_code = $currency_code;
        $account->currency_rate = $currency->rate;

        $account->thousands_separator = $currency->thousands_separator;
        $account->decimal_mark = $currency->decimal_mark;
        $account->precision = (int) $currency->precision;
        $account->symbol_first = $currency->symbol_first;
        $account->symbol = $currency->symbol;

        return response()->json($account);
    }
}
