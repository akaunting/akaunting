<?php

namespace App\Http\Controllers\Sales;

use App\Abstracts\Http\Controller;
use App\Exports\Sales\Customers as Export;
use App\Http\Requests\Common\Contact as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Sales\Customers as Import;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\UpdateContact;
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Sale\Invoice;
use App\Models\Setting\Currency;
use Date;
use Illuminate\Http\Request as BaseRequest;

class Customers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $customers = Contact::customer()->collect();

        return view('sales.customers.index', compact('customers'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Contact  $customer
     *
     * @return Response
     */
    public function show(Contact $customer)
    {
        $amounts = [
            'paid' => 0,
            'open' => 0,
            'overdue' => 0,
        ];

        $counts = [];

        // Handle invoices
        $invoices = Invoice::where('contact_id', $customer->id)->get();

        $counts['invoices'] = $invoices->count();

        $today = Date::today()->toDateString();

        foreach ($invoices as $item) {
            // Already in transactions
            if ($item->status == 'paid') {
                continue;
            }

            $transactions = 0;

            foreach ($item->transactions as $transaction) {
                $transactions += $transaction->getAmountConvertedToDefault();
            }

            // Check if it's open or overdue invoice
            if ($item->due_at > $today) {
                $amounts['open'] += $item->getAmountConvertedToDefault() - $transactions;
            } else {
                $amounts['overdue'] += $item->getAmountConvertedToDefault() - $transactions;
            }
        }

        // Handle transactions
        $transactions = Transaction::where('contact_id', $customer->id)->income()->get();

        $counts['transactions'] = $transactions->count();

        // Prepare data
        $transactions->each(function ($item) use (&$amounts) {
            $amounts['paid'] += $item->getAmountConvertedToDefault();
        });

        $limit = request('limit', setting('default.list_limit', '25'));
        $transactions = $this->paginate($transactions->sortByDesc('paid_at'), $limit);
        $invoices = $this->paginate($invoices->sortByDesc('invoiced_at'), $limit);

        return view('sales.customers.show', compact('customer', 'counts', 'amounts', 'transactions', 'invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('sales.customers.create', compact('currencies'));
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
        $response = $this->ajaxDispatch(new CreateContact($request));

        if ($response['success']) {
            $response['redirect'] = route('customers.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.customers', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('customers.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Contact  $customer
     *
     * @return Response
     */
    public function duplicate(Contact $customer)
    {
        $clone = $customer->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.customers', 1)]);

        flash($message)->success();

        return redirect()->route('customers.edit', $clone->id);
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

        $message = trans('messages.success.imported', ['type' => trans_choice('general.customers', 2)]);

        flash($message)->success();

        return redirect()->route('customers.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Contact  $customer
     *
     * @return Response
     */
    public function edit(Contact $customer)
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('sales.customers.edit', compact('customer', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Contact $customer
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Contact $customer, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, $request));

        if ($response['success']) {
            $response['redirect'] = route('customers.index');

            $message = trans('messages.success.updated', ['type' => $customer->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('customers.edit', $customer->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Contact $customer
     *
     * @return Response
     */
    public function enable(Contact $customer)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $customer->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Contact $customer
     *
     * @return Response
     */
    public function disable(Contact $customer)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $customer->name]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Contact $customer
     *
     * @return Response
     */
    public function destroy(Contact $customer)
    {
        $response = $this->ajaxDispatch(new DeleteContact($customer));

        $response['redirect'] = route('customers.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $customer->name]);

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
        return \Excel::download(new Export(), \Str::filename(trans_choice('general.customers', 2)) . '.xlsx');
    }

    public function currency(Contact $customer)
    {
        if (empty($customer)) {
            return response()->json([]);
        }

        $currency_code = setting('default.currency');

        if (isset($customer->currency_code)) {
            $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

            if (array_key_exists($customer->currency_code, $currencies)) {
                $currency_code = $customer->currency_code;
            }
        }

        // Get currency object
        $currency = Currency::where('code', $currency_code)->first();

        $customer->currency_name = $currency->name;
        $customer->currency_code = $currency_code;
        $customer->currency_rate = $currency->rate;

        $customer->thousands_separator = $currency->thousands_separator;
        $customer->decimal_mark = $currency->decimal_mark;
        $customer->precision = (int) $currency->precision;
        $customer->symbol_first = $currency->symbol_first;
        $customer->symbol = $currency->symbol;

        return response()->json($customer);
    }

    public function field(BaseRequest $request)
    {
        $html = '';

        if ($request['fields']) {
            foreach ($request['fields'] as $field) {
                switch ($field) {
                    case 'password':
                        $html .= \Form::passwordGroup('password', trans('auth.password.current'), 'key', [], 'col-md-6 password');
                        break;
                    case 'password_confirmation':
                        $html .= \Form::passwordGroup('password_confirmation', trans('auth.password.current_confirm'), 'key', [], 'col-md-6 password');
                        break;
                }
            }
        }

        $json = [
            'html' => $html
        ];

        return response()->json($json);
    }
}
