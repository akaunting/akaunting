<?php

namespace App\Http\Controllers\Sales;

use App\Abstracts\Http\Controller;
use App\Exports\Sales\Revenues as Export;
use App\Http\Requests\Banking\Transaction as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Sales\Revenues as Import;
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

class Revenues extends Controller
{
    use Contacts, Currencies, DateTime;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $revenues = Transaction::type('income')->with(['account', 'category', 'contact'])->isNotTransfer()->collect(['paid_at'=> 'desc']);

        $customers = Contact::type($this->getCustomerTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::type('income')->enabled()->orderBy('name')->pluck('name', 'id');

        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        return view('sales.revenues.index', compact('revenues', 'customers', 'categories', 'accounts'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('revenues.index');
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

        $customers = Contact::type($this->getCustomerTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::type('income')->enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('sales.revenues.create', compact('accounts', 'currencies', 'account_currency_code', 'currency', 'customers', 'categories', 'payment_methods'));
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
            $response['redirect'] = route('revenues.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.revenues', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('revenues.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Transaction  $revenue
     *
     * @return Response
     */
    public function duplicate(Transaction $revenue)
    {
        $clone = $revenue->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.revenues', 1)]);

        flash($message)->success();

        return redirect()->route('revenues.edit', $clone->id);
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

        $message = trans('messages.success.imported', ['type' => trans_choice('general.revenues', 2)]);

        flash($message)->success();

        return redirect()->route('revenues.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Transaction  $revenue
     *
     * @return Response
     */
    public function edit(Transaction $revenue)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $revenue->currency_code)->first();

        $customers = Contact::type($this->getCustomerTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::type('income')->enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $date_format = $this->getCompanyDateFormat();

        return view('sales.revenues.edit', compact('revenue', 'accounts', 'currencies', 'currency', 'customers', 'categories', 'payment_methods', 'date_format'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Transaction $revenue
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Transaction $revenue, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTransaction($revenue, $request));

        if ($response['success']) {
            $response['redirect'] = route('revenues.index');

            $message = trans('messages.success.updated', ['type' => trans_choice('general.revenues', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('revenues.edit', $revenue->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transaction $revenue
     *
     * @return Response
     */
    public function destroy(Transaction $revenue)
    {
        $response = $this->ajaxDispatch(new DeleteTransaction($revenue));

        $response['redirect'] = route('revenues.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.revenues', 1)]);

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
        return \Excel::download(new Export(), \Str::filename(trans_choice('general.revenues', 2)) . '.xlsx');
    }
}
