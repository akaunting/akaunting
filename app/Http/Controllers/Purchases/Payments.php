<?php

namespace App\Http\Controllers\Purchases;

use App\Abstracts\Http\Controller;
use App\Exports\Purchases\Payments as Export;
use App\Http\Requests\Banking\Transaction as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Purchases\Payments as Import;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Jobs\Banking\UpdateTransaction;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Traits\Contacts;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Utilities\Modules;

class Payments extends Controller
{
    use Contacts, Currencies, DateTime;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $payments = Transaction::type('expense')->with(['account', 'category', 'contact'])->isNotTransfer()->collect(['paid_at'=> 'desc']);

        $vendors = Contact::type($this->getVendorTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::type('expense')->enabled()->orderBy('name')->pluck('name', 'id');

        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        return view('purchases.payments.index', compact('payments', 'vendors', 'categories', 'accounts'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('payments.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('default.account'))->pluck('currency_code')->first();

        $currency = Currency::where('code', $account_currency_code)->first();

        $vendors = Contact::type($this->getVendorTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::type('expense')->enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('purchases.payments.create', compact('accounts', 'currencies', 'account_currency_code', 'currency', 'vendors', 'categories', 'payment_methods'));
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
        $response = $this->ajaxDispatch(new CreateTransaction($request));

        if ($response['success']) {
            $response['redirect'] = route('payments.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('payments.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Transaction $payment
     *
     * @return Response
     */
    public function duplicate(Transaction $payment)
    {
        $clone = $payment->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.payments', 1)]);

        flash($message)->success();

        return redirect()->route('payments.edit', $clone->id);
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
        \Excel::import(new Import(), $request->file('import'));

        $message = trans('messages.success.imported', ['type' => trans_choice('general.payments', 2)]);

        flash($message)->success();

        return redirect()->route('payments.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Transaction $payment
     *
     * @return Response
     */
    public function edit(Transaction $payment)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $payment->currency_code)->first();

        $vendors = Contact::type($this->getVendorTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::type('expense')->enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $date_format = $this->getCompanyDateFormat();

        return view('purchases.payments.edit', compact('payment', 'accounts', 'currencies', 'currency', 'vendors', 'categories', 'payment_methods', 'date_format'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Transaction $payment
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Transaction $payment, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTransaction($payment, $request));

        if ($response['success']) {
            $response['redirect'] = route('payments.index');

            $message = trans('messages.success.updated', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('payments.edit', $payment->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transaction $payment
     *
     * @return Response
     */
    public function destroy(Transaction $payment)
    {
        $response = $this->ajaxDispatch(new DeleteTransaction($payment));

        $response['redirect'] = route('payments.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.payments', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
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
        return \Excel::download(new Export(), \Str::filename(trans_choice('general.payments', 2)) . '.xlsx');
    }
}
