<?php

namespace App\Http\Controllers\Banking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banking\Reconciliation as Request;
use App\Http\Requests\Banking\ReconciliationCalculate as CalculateRequest;
use App\Models\Banking\Account;
use App\Models\Banking\Reconciliation;
use App\Models\Setting\Currency;
use Date;

class Reconciliations extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $reconciliations = Reconciliation::collect();

        $accounts = collect(Account::enabled()->orderBy('name')->pluck('name', 'id'));

        return view('banking.reconciliations.index', compact('reconciliations', 'accounts'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('reconciliations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $accounts = Account::enabled()->pluck('name', 'id');

        $account_id = request('account_id', setting('general.default_account'));
        $started_at = request('started_at', '0000-00-00');
        $ended_at = request('ended_at', '0000-00-00');

        $account = Account::find($account_id);

        $currency = $account->currency;

        $transactions = $this->getTransactions($account, $started_at, $ended_at);

        $opening_balance = $this->getOpeningBalance($account, $started_at, $ended_at);

        return view('banking.reconciliations.create', compact('accounts', 'account', 'currency', 'opening_balance', 'transactions'));
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
        $reconcile = $request->get('reconcile');
        $transactions = $request->get('transactions');

        Reconciliation::create([
            'company_id' => session('company_id'),
            'account_id' => $request->get('account_id'),
            'started_at' => $request->get('started_at'),
            'ended_at' => $request->get('ended_at'),
            'closing_balance' => $request->get('closing_balance'),
            'reconciled' => $reconcile ? 1 : 0,
        ]);

        if ($transactions) {
            foreach ($transactions as $key => $value) {
                $t = explode('_', $key);
                $m = '\\' . $t['1'];

                $transaction = $m::find($t[0]);
                $transaction->reconciled = 1;
                $transaction->save();
            }
        }

        $message = trans('messages.success.added', ['type' => trans_choice('general.reconciliations', 1)]);

        flash($message)->success();

        return redirect()->route('reconciliations.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Reconciliation  $reconciliation
     *
     * @return Response
     */
    public function edit(Reconciliation $reconciliation)
    {
        $account = $reconciliation->account;

        $currency = $account->currency;

        $transactions = $this->getTransactions($account, $reconciliation->started_at, $reconciliation->ended_at);

        $opening_balance = $this->getOpeningBalance($account, $reconciliation->started_at, $reconciliation->ended_at);

        return view('banking.reconciliations.edit', compact('reconciliation', 'account', 'currency', 'opening_balance', 'transactions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Reconciliation  $reconciliation
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Reconciliation $reconciliation, Request $request)
    {
        $reconcile = $request->get('reconcile');
        $transactions = $request->get('transactions');

        $reconciliation->reconciled = $reconcile ? 1 : 0;
        $reconciliation->save();

        if ($transactions) {
            foreach ($transactions as $key => $value) {
                $t = explode('_', $key);
                $m = '\\' . $t['1'];

                $transaction = $m::find($t[0]);
                $transaction->reconciled = 1;
                $transaction->save();
            }
        }

        $message = trans('messages.success.updated', ['type' => trans_choice('general.reconciliations', 1)]);

        flash($message)->success();

        return redirect()->route('reconciliations.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reconciliation  $reconciliation
     *
     * @return Response
     */
    public function destroy(Reconciliation $reconciliation)
    {
        $reconciliation->delete();

        $models = [
            'App\Models\Expense\Payment',
            'App\Models\Expense\BillPayment',
            'App\Models\Income\Revenue',
            'App\Models\Income\InvoicePayment',
        ];

        foreach ($models as $model) {
            $m = '\\' . $model;

            $m::where('account_id', $reconciliation->account_id)
                ->reconciled()
                ->whereBetween('paid_at', [$reconciliation->started_at, $reconciliation->ended_at])->each(function ($item) {
                    $item->reconciled = 0;
                    $item->save();
            });
        }

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.reconciliations', 1)]);

        flash($message)->success();

        return redirect()->route('reconciliations.index');
    }

    /**
     * Add transactions array.
     *
     * @param $account_id
     * @param $started_at
     * @param $ended_at
     *
     * @return array
     */
    protected function getTransactions($account, $started_at, $ended_at)
    {
        $started = explode(' ', $started_at);
        $ended = explode(' ', $ended_at);

        $models = [
            'App\Models\Expense\Payment',
            'App\Models\Expense\BillPayment',
            'App\Models\Income\Revenue',
            'App\Models\Income\InvoicePayment',
        ];

        $transactions = [];

        foreach ($models as $model) {
            $m = '\\' . $model;

            $m::where('account_id', $account->id)->whereBetween('paid_at', [$started[0], $ended[0]])->each(function($item) use(&$transactions, $model) {
                $item->model = $model;

                if (($model == 'App\Models\Income\InvoicePayment') || ($model == 'App\Models\Income\Revenue')) {
                    if ($item->invoice) {
                        $item->contact = $item->invoice->customer;
                    } else {
                        $item->contact = $item->customer;
                    }
                } else {
                    if ($item->bill) {
                        $item->contact = $item->bill->vendor;
                    } else {
                        $item->contact = $item->vendor;
                    }
                }

                $transactions[] = $item;
            });
        }

        return collect($transactions)->sortByDesc('paid_at');
    }

    /**
     * Get the opening balance
     *
     * @param $account
     * @param $started_at
     *
     * @return string
     */
    public function getOpeningBalance($account, $started_at)
    {
        // Opening Balance
        $total = $account->opening_balance;

        // Sum invoices
        $invoice_payments = $account->invoice_payments()->whereDate('paid_at', '<', $started_at)->get();
        foreach ($invoice_payments as $item) {
            $total += $item->amount;
        }

        // Sum revenues
        $revenues = $account->revenues()->whereDate('paid_at', '<', $started_at)->get();
        foreach ($revenues as $item) {
            $total += $item->amount;
        }

        // Subtract bills
        $bill_payments = $account->bill_payments()->whereDate('paid_at', '<', $started_at)->get();
        foreach ($bill_payments as $item) {
            $total -= $item->amount;
        }

        // Subtract payments
        $payments = $account->payments()->whereDate('paid_at', '<', $started_at)->get();
        foreach ($payments as $item) {
            $total -= $item->amount;
        }

        return $total;
    }

    public function calculate(CalculateRequest $request)
    {
        $currency_code = $request['currency_code'];
        $closing_balance = $request['closing_balance'];

        $json = new \stdClass();

        $cleared_amount = $difference = $income_total = $expense_total = 0;

        if ($transactions = $request['transactions']) {
            $opening_balance = $request['opening_balance'];

            foreach ($transactions as $key => $value) {
                $model = explode('_', $key);

                if (($model[1] == 'App\Models\Income\InvoicePayment') || ($model[1] == 'App\Models\Income\Revenue')) {
                    $income_total += $value;
                } else {
                    $expense_total += $value;
                }
            }

            $cleared_amount = $opening_balance + ($income_total - $expense_total);
        }

        $difference = $closing_balance - $cleared_amount;

        $json->closing_balance = money($closing_balance, $currency_code, true)->format();
        $json->cleared_amount = money($cleared_amount, $currency_code, true)->format();
        $json->difference = money($difference, $currency_code, true)->format();
        $json->difference_raw = (int) $difference;

        return response()->json($json);
    }
}
