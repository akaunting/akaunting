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
use App\Models\Document\Document;
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
        $customers = Contact::with('invoices.transactions')->customer()->collect();

        return $this->response('sales.customers.index', compact('customers'));
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
        $invoices = Document::invoice()->with('transactions')->where('contact_id', $customer->id)->get();

        $counts['invoices'] = $invoices->count();

        $today = Date::today()->toDateString();

        foreach ($invoices as $item) {
            // Already in transactions
            if ($item->status == 'paid' || $item->status == 'cancelled') {
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
        $transactions = Transaction::with('account', 'category')->where('contact_id', $customer->id)->income()->get();

        $counts['transactions'] = $transactions->count();

        // Prepare data
        $transactions->each(function ($item) use (&$amounts) {
            $amounts['paid'] += $item->getAmountConvertedToDefault();
        });

        $limit = (int) request('limit', setting('default.list_limit', '25'));
        $transactions = $this->paginate($transactions->sortByDesc('paid_at'), $limit);
        $invoices = $this->paginate($invoices->sortByDesc('issued_at'), $limit);

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
            $response['redirect'] = route('customers.show', $response['data']->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.customers', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('customers.create');

            $message = $response['message'];

            flash($message)->error()->important();
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
        $response = $this->importExcel(new Import, $request, trans_choice('general.customers', 2));

        if ($response['success']) {
            $response['redirect'] = route('customers.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['sales', 'customers']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
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

            flash($message)->error()->important();
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
        return $this->exportExcel(new Export, trans_choice('general.customers', 2));
    }

    public function createInvoice(Contact $customer)
    {
        $data['contact'] = $customer;

        return redirect()->route('invoices.create')->withInput($data);
    }

    public function createRevenue(Contact $customer)
    {
        $data['contact'] = $customer;

        return redirect()->route('revenues.create')->withInput($data);
    }
}
