<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Account as Request;
use App\Jobs\Banking\CreateAccount;
use App\Jobs\Banking\DeleteAccount;
use App\Jobs\Banking\UpdateAccount;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use App\Utilities\Reports as Utility;
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
        $accounts = Account::with('income_transactions', 'expense_transactions')->collect();

        return $this->response('banking.accounts.index', compact('accounts'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show(Account $account)
    {
        // Handle transactions
        $transactions = Transaction::with('account', 'category')->where('account_id', $account->id)->collect('paid_at');

        $transfers = Transfer::with('transaction')->all()->filter(function ($transfer) use($account) {
            if ($transfer->expense_account->id == $account->id || $transfer->income_account->id == $account->id) {
                return true;
            }

            return false;
        })->sortByDesc(function ($transfer) {
            return $transfer->expense_transaction->paid_at;
        });

        $limit = (int) request('limit', setting('default.list_limit', '25'));
        $transfers = $this->paginate($transfers, $limit);

        return view('banking.accounts.show', compact('account', 'transactions', 'transfers'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        $currency = Currency::where('code', '=', setting('default.currency'))->first();

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
        $response = $this->ajaxDispatch(new CreateAccount($request));

        if ($response['success']) {
            $response['redirect'] = route('accounts.show', $response['data']->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.accounts', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('accounts.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Account $account
     *
     * @return Response
     */
    public function duplicate(Account $account)
    {
        $clone = $account->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.accounts', 1)]);

        flash($message)->success();

        return redirect()->route('accounts.edit', $clone->id);
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

        $account->default_account = ($account->id == setting('default.account')) ? 1 : 0;

        $currency = Currency::where('code', '=', $account->currency_code)->first();

        return view('banking.accounts.edit', compact('account', 'currencies', 'currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Account $account
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Account $account, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateAccount($account, $request));

        if ($response['success']) {
            $response['redirect'] = route('accounts.show', $account->id);

            $message = trans('messages.success.updated', ['type' => $account->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('accounts.edit', $account->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Account $account
     *
     * @return Response
     */
    public function enable(Account $account)
    {
        $response = $this->ajaxDispatch(new UpdateAccount($account, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $account->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Account $account
     *
     * @return Response
     */
    public function disable(Account $account)
    {
        $response = $this->ajaxDispatch(new UpdateAccount($account, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $account->name]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Account $account
     *
     * @return Response
     */
    public function destroy(Account $account)
    {
        $response = $this->ajaxDispatch(new DeleteAccount($account));

        $response['redirect'] = route('accounts.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $account->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function createRevenue(Account $account)
    {
        $data['account_id'] = $account->id;

        return redirect()->route('revenues.create')->withInput($data);
    }

    public function createPayment(Account $account)
    {
        $data['account_id'] = $account->id;

        return redirect()->route('payments.create')->withInput($data);
    }

    public function createTransfer(Account $account)
    {
        $data['from_account_id'] = $account->id;

        return redirect()->route('transfers.create')->withInput($data);
    }

    public function seePerformance(Account $account)
    {
        $data['account_id'] = $account->id;

        $report = Utility::getClassInstance('App\Reports\IncomeExpenseSummary');

        if (empty($report) || empty($report->model)) {
            $message = trans('accounts.create_report');

            flash($message)->warning()->important();

            return redirect()->route('reports.create');
        }

        return redirect()->route('reports.show', $report->model->id)->withInput($data);
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

        $currency_code = setting('default.currency');

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
