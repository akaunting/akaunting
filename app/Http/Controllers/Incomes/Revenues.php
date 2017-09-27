<?php

namespace App\Http\Controllers\Incomes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Income\Revenue as Request;
use App\Models\Banking\Account;
use App\Models\Income\Customer;
use App\Models\Income\Revenue;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Uploads;
use App\Utilities\Modules;

class Revenues extends Controller
{
    use DateTime, Currencies, Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $revenues = Revenue::with(['account', 'category', 'customer'])->collect();

        $customers = collect(Customer::enabled()->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.customers', 2)]), '');

        $categories = collect(Category::enabled()->type('income')->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.categories', 2)]), '');

        $accounts = collect(Account::enabled()->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.accounts', 2)]), '');

        return view('incomes.revenues.index', compact('revenues', 'customers', 'categories', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $accounts = Account::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('general.default_account'))->pluck('currency_code')->first();

        $customers = Customer::enabled()->pluck('name', 'id');

        $categories = Category::enabled()->type('income')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('incomes.revenues.create', compact('accounts', 'currencies', 'account_currency_code', 'customers', 'categories', 'payment_methods'));
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
        // Get currency object
        $currency = Currency::where('code', $request['currency_code'])->first();

        $request['currency_code'] = $currency->code;
        $request['currency_rate'] = $currency->rate;

        // Upload attachment
        $attachment_path = $this->getUploadedFilePath($request->file('attachment'), 'revenues');
        if ($attachment_path) {
            $request['attachment'] = $attachment_path;
        }

        Revenue::create($request->input());

        $message = trans('messages.success.added', ['type' => trans_choice('general.revenues', 1)]);

        flash($message)->success();

        return redirect('incomes/revenues');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Revenue  $revenue
     *
     * @return Response
     */
    public function edit(Revenue $revenue)
    {
        $accounts = Account::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', $revenue->account_id)->pluck('currency_code')->first();

        $customers = Customer::enabled()->pluck('name', 'id');

        $categories = Category::enabled()->type('income')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('incomes.revenues.edit', compact('revenue', 'accounts', 'currencies', 'account_currency_code', 'customers', 'categories', 'payment_methods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Revenue  $revenue
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Revenue $revenue, Request $request)
    {
        // Get currency
        $currency = Currency::where('code', $request['currency_code'])->first();

        $request['currency_code'] = $currency->code;
        $request['currency_rate'] = $currency->rate;

        // Upload attachment
        $attachment_path = $this->getUploadedFilePath($request->file('attachment'), 'revenues');
        if ($attachment_path) {
            $request['attachment'] = $attachment_path;
        }

        $revenue->update($request->input());

        $message = trans('messages.success.updated', ['type' => trans_choice('general.revenues', 1)]);

        flash($message)->success();

        return redirect('incomes/revenues');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Revenue  $revenue
     *
     * @return Response
     */
    public function destroy(Revenue $revenue)
    {
        $revenue->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.revenues', 1)]);

        flash($message)->success();

        return redirect('incomes/revenues');
    }
}
