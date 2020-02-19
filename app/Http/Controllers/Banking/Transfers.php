<?php

namespace App\Http\Controllers\Banking;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Transfer as Request;
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
        $data = [];

        $items = Transfer::with([
            'expense_transaction', 'expense_transaction.account', 'income_transaction', 'income_transaction.account'
        ])->collect(['expense_transaction.paid_at' => 'desc']);

        foreach ($items as $item) {
            $income_transaction = $item->income_transaction;
            $expense_transaction = $item->expense_transaction;

            $name = trans('transfers.messages.delete', [
                'from' => $expense_transaction->account->name,
                'to' => $income_transaction->account->name,
                'amount' => money($expense_transaction->amount, $expense_transaction->currency_code, true)
            ]);

            $data[] = (object) [
                'id' => $item->id,
                'name' => $name,
                'from_account' => $expense_transaction->account->name,
                'to_account' => $income_transaction->account->name,
                'amount' => $expense_transaction->amount,
                'currency_code' => $expense_transaction->currency_code,
                'paid_at' => $expense_transaction->paid_at,
            ];
        }

        $special_key = array(
            'expense_transaction.name' => 'from_account',
            'income_transaction.name' => 'to_account',
        );

        $request = request();

        if (isset($request['sort']) && array_key_exists($request['sort'], $special_key)) {
            $sort_order = array();

            foreach ($data as $key => $value) {
                $sort = $request['sort'];

                if (array_key_exists($request['sort'], $special_key)) {
                    $sort = $special_key[$request['sort']];
                }

                $sort_order[$key] = $value->{$sort};
            }

            $sort_type = (isset($request['order']) && $request['order'] == 'asc') ? SORT_ASC : SORT_DESC;

            array_multisort($sort_order, $sort_type, $data);
        }

        $transfers = $this->paginate($data);

        $accounts = collect(Account::enabled()->orderBy('name')->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.accounts', 2)]), '');

        return view('banking.transfers.index', compact('transfers', 'accounts'));
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

        $currency = Currency::where('code', setting('default.currency'))->first();

        return view('banking.transfers.create', compact('accounts', 'payment_methods', 'currency'));
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

            flash($message)->error();
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
        $transfer['to_account_id'] = $transfer->income_transaction->account_id;
        $transfer['transferred_at'] = Date::parse($transfer->expense_transaction->paid_at)->format('Y-m-d');
        $transfer['description'] = $transfer->expense_transaction->description;
        $transfer['amount'] = $transfer->expense_transaction->amount;
        $transfer['payment_method'] = $transfer->expense_transaction->payment_method;
        $transfer['reference'] = $transfer->expense_transaction->reference;

        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $account = $transfer->expense_transaction->account;

        $currency = Currency::where('code', $account->currency_code)->first();

        return view('banking.transfers.edit', compact('transfer', 'accounts', 'payment_methods', 'currency'));
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

            flash($message)->error();
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

            flash($message)->error();
        }

        return response()->json($response);
    }
}
