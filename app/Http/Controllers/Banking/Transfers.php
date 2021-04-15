<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Transfer as Request;
use App\Exports\Banking\Transfers as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Banking\Transfers as Import;
use App\Jobs\Banking\CreateTransfer;
use App\Jobs\Banking\UpdateTransfer;
use App\Jobs\Banking\DeleteTransfer;
use App\Models\Banking\Account;
use App\Models\Banking\Transfer;
use App\Models\Setting\Currency;
use App\Utilities\Modules;
use Date;

class Transfers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $transfers = Transfer::with(
            'expense_transaction', 'expense_transaction.account', 'income_transaction', 'income_transaction.account'
        )->collect(['expense_transaction.paid_at' => 'desc']);

        return $this->response('banking.transfers.index', compact('transfers'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('transfers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $currencies = Currency::enabled()->orderBy('name')->get()->makeHidden(['id', 'company_id', 'created_at', 'updated_at', 'deleted_at']);

        $currency = Currency::where('code', setting('default.currency'))->first();

        return view('banking.transfers.create', compact('accounts', 'payment_methods', 'currencies', 'currency'));
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
        $response = $this->ajaxDispatch(new CreateTransfer($request));

        if ($response['success']) {
            $response['redirect'] = route('transfers.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.transfers', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('transfers.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
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
        $response = $this->importExcel(new Import, $request, trans_choice('general.transfers', 2));

        if ($response['success']) {
            $response['redirect'] = route('transfers.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['banking', 'transfers']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Transfer  $transfer
     *
     * @return Response
     */
    public function edit(Transfer $transfer)
    {
        $transfer['from_account_id'] = $transfer->expense_transaction->account_id;
        $transfer['from_currency_code'] = $transfer->expense_transaction->currency_code;
        $transfer['from_account_rate'] = $transfer->expense_transaction->currency_rate;
        $transfer['to_account_id'] = $transfer->income_transaction->account_id;
        $transfer['to_currency_code'] = $transfer->income_transaction->currency_code;
        $transfer['to_account_rate'] = $transfer->income_transaction->currency_rate;
        $transfer['transferred_at'] = Date::parse($transfer->expense_transaction->paid_at)->format('Y-m-d');
        $transfer['description'] = $transfer->expense_transaction->description;
        $transfer['amount'] = $transfer->expense_transaction->amount;
        $transfer['payment_method'] = $transfer->expense_transaction->payment_method;
        $transfer['reference'] = $transfer->expense_transaction->reference;

        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $account = $transfer->expense_transaction->account;

        $currencies = Currency::enabled()->orderBy('name')->get()->makeHidden(['id', 'company_id', 'created_at', 'updated_at', 'deleted_at']);

        $currency = Currency::where('code', $account->currency_code)->first();

        return view('banking.transfers.edit', compact('transfer', 'accounts', 'payment_methods', 'currencies', 'currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $id
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Transfer $transfer, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTransfer($transfer, $request));

        if ($response['success']) {
            $response['redirect'] = route('transfers.index');

            $message = trans('messages.success.updated', ['type' => trans_choice('general.transfers', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('transfers.edit', $transfer->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     *
     * @return Response
     */
    public function destroy(Transfer $transfer)
    {
        $response = $this->ajaxDispatch(new DeleteTransfer($transfer));

        $response['redirect'] = route('transfers.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.transfers', 1)]);

            flash($message)->success();
        } else {
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
        return $this->exportExcel(new Export, trans_choice('general.transfers', 2));
    }
}
