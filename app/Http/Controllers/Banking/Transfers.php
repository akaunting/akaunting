<?php

namespace App\Http\Controllers\Banking;

use App\Http\Controllers\Controller;

use App\Http\Requests\Banking\Transfer as Request;
use App\Models\Banking\Account;
use App\Models\Banking\Transfer;
use App\Models\Expense\Payment;
use App\Models\Income\Revenue;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use Date;

use App\Utilities\Modules;

class Transfers extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $request = request();

        $items = Transfer::with(['payment', 'payment.account', 'revenue', 'revenue.account'])->collect(['payment.paid_at' => 'desc']);

        $accounts = collect(Account::enabled()->orderBy('name')->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.accounts', 2)]), '');

        $transfers = array();

        foreach ($items as $item) {
            $revenue = $item->revenue;
            $payment = $item->payment;

            $name = trans('transfers.messages.delete', [
                'from' => $payment->account->name,
                'to' => $revenue->account->name,
                'amount' => money($payment->amount, $payment->currency_code, true)
            ]);

            $transfers[] = (object)[
                'id' => $item->id,
                'name' => $name,
                'from_account' => $payment->account->name,
                'to_account' => $revenue->account->name,
                'amount' => $payment->amount,
                'currency_code' => $payment->currency_code,
                'paid_at' => $payment->paid_at,
            ];
        }

        $special_key = array(
            'payment.name' => 'from_account',
            'revenue.name' => 'to_account',
        );

        if (isset($request['sort']) && array_key_exists($request['sort'], $special_key)) {
            $sort_order = array();

            foreach ($transfers as $key => $value) {
                $sort = $request['sort'];

                if (array_key_exists($request['sort'], $special_key)) {
                    $sort = $special_key[$request['sort']];
                }

                $sort_order[$key] = $value->{$sort};
            }

            $sort_type = (isset($request['order']) && $request['order'] == 'asc') ? SORT_ASC : SORT_DESC;

            array_multisort($sort_order, $sort_type, $transfers);
        }

        return view('banking.transfers.index', compact('transfers', 'items', 'accounts'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect('banking/transfers');
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

        $currency = Currency::where('code', '=', setting('general.default_currency', 'USD'))->first();

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
        $currencies = Currency::enabled()->pluck('rate', 'code')->toArray();

        $payment_currency_code = Account::where('id', $request['from_account_id'])->pluck('currency_code')->first();
        $revenue_currency_code = Account::where('id', $request['to_account_id'])->pluck('currency_code')->first();

        $payment_request = [
            'company_id' => $request['company_id'],
            'account_id' => $request['from_account_id'],
            'paid_at' => $request['transferred_at'],
            'currency_code' => $payment_currency_code,
            'currency_rate' => $currencies[$payment_currency_code],
            'amount' => $request['amount'],
            'vendor_id' => 0,
            'description' => $request['description'],
            'category_id' => Category::transfer(), // Transfer Category ID
            'payment_method' => $request['payment_method'],
            'reference' => $request['reference'],
        ];

        $payment = Payment::create($payment_request);

        // Convert amount if not same currency
        if ($payment_currency_code != $revenue_currency_code) {
            $default_currency = setting('general.default_currency', 'USD');

            $default_amount = $request['amount'];

            if ($default_currency != $payment_currency_code) {
                $default_amount_model = new Transfer();

                $default_amount_model->default_currency_code = $default_currency;
                $default_amount_model->amount = $request['amount'];
                $default_amount_model->currency_code = $payment_currency_code;
                $default_amount_model->currency_rate = $currencies[$payment_currency_code];

                $default_amount = $default_amount_model->getDivideConvertedAmount();
            }

            $transfer_amount = new Transfer();

            $transfer_amount->default_currency_code = $payment_currency_code;
            $transfer_amount->amount = $default_amount;
            $transfer_amount->currency_code = $revenue_currency_code;
            $transfer_amount->currency_rate = $currencies[$revenue_currency_code];

            $amount = $transfer_amount->getDynamicConvertedAmount();
        } else {
            $amount = $request['amount'];
        }

        $revenue_request = [
            'company_id' => $request['company_id'],
            'account_id' => $request['to_account_id'],
            'paid_at' => $request['transferred_at'],
            'currency_code' => $revenue_currency_code,
            'currency_rate' => $currencies[$revenue_currency_code],
            'amount' => $amount,
            'customer_id' => 0,
            'description' => $request['description'],
            'category_id' => Category::transfer(), // Transfer Category ID
            'payment_method' => $request['payment_method'],
            'reference' => $request['reference'],
        ];

        $revenue = Revenue::create($revenue_request);

        $transfer_request = [
            'company_id' => $request['company_id'],
            'payment_id' => $payment->id,
            'revenue_id' => $revenue->id,
        ];

        Transfer::create($transfer_request);

        $message = trans('messages.success.added', ['type' => trans_choice('general.transfers', 1)]);

        flash($message)->success();

        return redirect('banking/transfers');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function edit(Transfer $transfer)
    {
        $payment = Payment::findOrFail($transfer->payment_id);
        $revenue = Revenue::findOrFail($transfer->revenue_id);

        $transfer['from_account_id'] = $payment->account_id;
        $transfer['to_account_id'] = $revenue->account_id;
        $transfer['transferred_at'] = Date::parse($payment->paid_at)->format('Y-m-d');
        $transfer['description'] = $payment->description;
        $transfer['amount'] = $payment->amount;
        $transfer['payment_method'] = $payment->payment_method;
        $transfer['reference'] = $payment->reference;

        $account = Account::find($payment->account_id);
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $currency = Currency::where('code', '=', $account->currency_code)->first();

        return view('banking.transfers.edit', compact('transfer', 'accounts', 'payment_methods', 'currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Transfer  $transfer
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Transfer $transfer, Request $request)
    {
        $currencies = Currency::enabled()->pluck('rate', 'code')->toArray();

        $payment_currency_code = Account::where('id', $request['from_account_id'])->pluck('currency_code')->first();
        $revenue_currency_code = Account::where('id', $request['to_account_id'])->pluck('currency_code')->first();

        $payment = Payment::findOrFail($transfer->payment_id);
        $revenue = Revenue::findOrFail($transfer->revenue_id);

        $payment_request = [
            'company_id' => $request['company_id'],
            'account_id' => $request['from_account_id'],
            'paid_at' => $request['transferred_at'],
            'currency_code' => $payment_currency_code,
            'currency_rate' => $currencies[$payment_currency_code],
            'amount' => $request['amount'],
            'vendor_id' => 0,
            'description' => $request['description'],
            'category_id' => Category::transfer(), // Transfer Category ID
            'payment_method' => $request['payment_method'],
            'reference' => $request['reference'],
        ];

        $payment->update($payment_request);

        // Convert amount if not same currency
        if ($payment_currency_code != $revenue_currency_code) {
            $default_currency = setting('general.default_currency', 'USD');

            $default_amount = $request['amount'];

            if ($default_currency != $payment_currency_code) {
                $default_amount_model = new Transfer();

                $default_amount_model->default_currency_code = $default_currency;
                $default_amount_model->amount = $request['amount'];
                $default_amount_model->currency_code = $payment_currency_code;
                $default_amount_model->currency_rate = $currencies[$payment_currency_code];

                $default_amount = $default_amount_model->getDivideConvertedAmount();
            }

            $transfer_amount = new Transfer();

            $transfer_amount->default_currency_code = $payment_currency_code;
            $transfer_amount->amount = $default_amount;
            $transfer_amount->currency_code = $revenue_currency_code;
            $transfer_amount->currency_rate = $currencies[$revenue_currency_code];

            $amount = $transfer_amount->getDynamicConvertedAmount();
        } else {
            $amount = $request['amount'];
        }

        $revenue_request = [
            'company_id' => $request['company_id'],
            'account_id' => $request['to_account_id'],
            'paid_at' => $request['transferred_at'],
            'currency_code' => $revenue_currency_code,
            'currency_rate' => $currencies[$revenue_currency_code],
            'amount' => $amount,
            'customer_id' => 0,
            'description' => $request['description'],
            'category_id' => Category::transfer(), // Transfer Category ID
            'payment_method' => $request['payment_method'],
            'reference' => $request['reference'],
        ];

        $revenue->update($revenue_request);

        $transfer_request = [
            'company_id' => $request['company_id'],
            'payment_id' => $payment->id,
            'revenue_id' => $revenue->id,
        ];

        $transfer->update($transfer_request);

        $message = trans('messages.success.updated', ['type' => trans_choice('general.transfers', 1)]);

        flash($message)->success();

        return redirect('banking/transfers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transfer  $transfer
     *
     * @return Response
     */
    public function destroy(Transfer $transfer)
    {
        $payment = Payment::findOrFail($transfer->payment_id);
        $revenue = Revenue::findOrFail($transfer->revenue_id);

        $payment->delete();
        $revenue->delete();
        $transfer->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.transfers', 1)]);

        flash($message)->success();

        return redirect('banking/transfers');
    }
}
