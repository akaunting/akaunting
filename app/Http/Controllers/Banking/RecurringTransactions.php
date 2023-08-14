<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Exports\Banking\RecurringTransactions as Export;
use App\Http\Requests\Banking\Transaction as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Banking\RecurringTransactions as Import;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\UpdateTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Common\Recurring;
use App\Models\Setting\Currency;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Transactions as TransactionsTrait;

class RecurringTransactions extends Controller
{
    use Currencies, DateTime, TransactionsTrait;

    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-banking-transactions')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-banking-transactions')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-banking-transactions')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-banking-transactions')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $transactions = Transaction::with('category', 'recurring')->isRecurring()->collect(['paid_at'=> 'desc']);

        return $this->response('banking.recurring_transactions.index', compact('transactions'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show(Transaction $recurring_transaction)
    {
        $recurring_transaction->load(['category', 'recurring', 'children']);

        $title = ($recurring_transaction->type == 'income-recurring') ? trans_choice('general.recurring_incomes', 1) : trans_choice('general.recurring_expenses', 1);

        return view('banking.recurring_transactions.show', compact('recurring_transaction', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $type = $this->getTypeRecurringTransaction(request()->get('type', 'income-recurring'));
        $real_type = $this->getTypeTransaction(request()->get('real_type', $this->getRealTypeOfRecurringTransaction($type)));
        $contact_type = config('type.transaction.' . $real_type . '.contact_type', 'customer');

        $number = $this->getNextTransactionNumber('-recurring');

        $account_currency_code = Account::where('id', setting('default.account'))->pluck('currency_code')->first();

        $currency = Currency::where('code', $account_currency_code)->first();

        return view('banking.recurring_transactions.create', compact(
            'type',
            'real_type',
            'number',
            'contact_type',
            'account_currency_code',
            'currency'
        ));
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
        $response = $this->ajaxDispatch(new CreateTransaction($request->merge(['paid_at' => $request->get('recurring_started_at')])));

        if ($response['success']) {
            $response['redirect'] = route('recurring-transactions.show', $response['data']->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.recurring_transactions', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('recurring-transactions.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Transaction  $recurring_transaction
     *
     * @return Response
     */
    public function duplicate(Transaction $recurring_transaction)
    {
        $clone = $recurring_transaction->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.recurring_transactions', 1)]);

        flash($message)->success();

        return redirect()->route('recurring-transactions.edit', $clone->id);
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportRequest  $request
     *
     * @return Response
     */
    public function import(ImportRequest $request)
    {
        $response = $this->importExcel(new Import, $request, trans_choice('general.recurring_transactions', 2));

        if ($response['success']) {
            $response['redirect'] = route('recurring-transactions.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['banking', 'recurring-transactions']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Transaction  $recurring_transaction
     *
     * @return Response
     */
    public function edit(Transaction $recurring_transaction)
    {
        $type = $recurring_transaction->type;
        $real_type = $this->getTypeTransaction(request()->get('real_type', $this->getRealTypeOfRecurringTransaction($type)));
        $contact_type = config('type.transaction.' . $real_type . '.contact_type', 'customer');

        $number = $this->getNextTransactionNumber('-recurring');

        $currency = Currency::where('code', $recurring_transaction->currency_code)->first();

        $date_format = $this->getCompanyDateFormat();

        return view('banking.recurring_transactions.edit', compact(
            'type',
            'real_type',
            'number',
            'contact_type',
            'recurring_transaction',
            'currency',
            'date_format'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Transaction $recurring_transaction
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Transaction $recurring_transaction, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTransaction($recurring_transaction, $request->merge(['paid_at' => $request->get('recurring_started_at')])));

        if ($response['success']) {
            $response['redirect'] = route('recurring-transactions.show', $recurring_transaction->id);

            $message = trans('messages.success.updated', ['type' => trans_choice('general.recurring_transactions', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('recurring-transactions.edit', $recurring_transaction->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }
    
    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('general.recurring_transactions', 2));
    }

    /**
     * End recurring template.
     *
     * @return Response
     */
    public function end(Transaction $recurring_transaction)
    {
        $response = $this->ajaxDispatch(new UpdateTransaction($recurring_transaction, [
            'recurring_frequency' => $recurring_transaction->recurring->frequency,
            'recurring_interval' => $recurring_transaction->recurring->interval,
            'recurring_started_at' => $recurring_transaction->recurring->started_at,
            'recurring_limit' => $recurring_transaction->recurring->limit,
            'recurring_limit_count' => $recurring_transaction->recurring->limit_count,
            'recurring_limit_date' => $recurring_transaction->recurring->limit_date,
            'created_from' => $recurring_transaction->created_from,
            'created_by' => $recurring_transaction->created_by,
            'recurring_status' => Recurring::END_STATUS,
        ]));

        if ($response['success']) {
            $message = trans('messages.success.ended', ['type' => trans_choice('general.recurring_transactions', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return redirect()->route('recurring-transactions.index');
    }
}
