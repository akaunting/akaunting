<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Reconciliation as Request;
use App\Jobs\Banking\CreateReconciliation;
use App\Jobs\Banking\DeleteReconciliation;
use App\Jobs\Banking\UpdateReconciliation;
use App\Models\Banking\Account;
use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;
use App\Traits\Reconciliations as ReconciliationRules;
use App\Utilities\Date;

class Reconciliations extends Controller
{
    // getOpeningBalance(), the page starts the difference from it as the server does
    use ReconciliationRules;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $reconciliations = Reconciliation::with('account')->collect();

        $reconciled_amount = money($reconciliations->where('reconciled', 1)->sum('closing_balance'));
        $in_progress_amount = money($reconciliations->where('reconciled', 0)->sum('closing_balance'));

        $summary_amounts = [
            'amount_exact'              => $reconciled_amount->format(),
            'amount_for_humans'         => $reconciled_amount->formatForHumans(),
            'in_progress_exact'         => $in_progress_amount->format(),
            'in_progress_for_humans'    => $in_progress_amount->formatForHumans(),
        ];

        return $this->response('banking.reconciliations.index', compact('reconciliations', 'summary_amounts'));
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
        $account_id = request('account_id', setting('default.account'));
        $started_at = request('started_at', Date::now()->firstOfMonth()->toDateString());
        $ended_at = request('ended_at', Date::now()->endOfMonth()->toDateString());

        $account = Account::find($account_id);

        $currency = $account->currency;

        $transactions = $this->getTransactions($account, $started_at, $ended_at);

        $opening_balance = $this->getOpeningBalance($account, $started_at);

        // Told before the transactions are checked, saving it is refused (Requests\Banking\Reconciliation)
        $overlapping = Reconciliation::reconciledOverlapping($account->id, $started_at, $ended_at)->first();

        return view('banking.reconciliations.create', compact('account', 'currency', 'opening_balance', 'transactions', 'overlapping'));
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
        $response = $this->ajaxDispatch(new CreateReconciliation($request));

        if ($response['success']) {
            $response['redirect'] = route('reconciliations.index');

            $message = trans('messages.success.created', ['type' => trans_choice('general.reconciliations', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('reconciliations.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
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

        $overlapping = Reconciliation::reconciledOverlapping($account->id, $reconciliation->started_at, $reconciliation->ended_at, $reconciliation->id)->first();

        return view('banking.reconciliations.edit', compact('reconciliation', 'account', 'currency', 'opening_balance', 'transactions', 'overlapping'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Reconciliation $reconciliation
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Reconciliation $reconciliation, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateReconciliation($reconciliation, $request));

        if ($response['success']) {
            $response['redirect'] = route('reconciliations.index');

            $message = trans('messages.success.updated', ['type' => trans_choice('general.reconciliations', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('reconciliations.edit', $reconciliation->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reconciliation $reconciliation
     *
     * @return Response
     */
    public function destroy(Reconciliation $reconciliation)
    {
        $response = $this->ajaxDispatch(new DeleteReconciliation($reconciliation));

        $response['redirect'] = route('reconciliations.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.reconciliations', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Add transactions array.
     *
     * @param $account
     * @param $started_at
     * @param $ended_at
     *
     * @return mixed
     */
    protected function getTransactions($account, $started_at, $ended_at)
    {
        $started = explode(' ', $started_at)[0] . ' 00:00:00';
        $ended = explode(' ', $ended_at)[0] . ' 23:59:59';

        $transactions = Transaction::with('account', 'contact')->where('account_id', $account->id)->whereBetween('paid_at', [$started, $ended])->get();

        return collect($transactions)->sortByDesc('paid_at');
    }
}
