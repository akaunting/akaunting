<?php

namespace App\Http\Controllers\Purchases;

use App\Abstracts\Http\Controller;
use App\Exports\Purchases\Vendors as Export;
use App\Http\Requests\Common\Contact as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Purchases\Vendors as Import;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\UpdateContact;
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Purchase\Bill;
use App\Models\Setting\Currency;
use App\Traits\Contacts;
use Date;

class Vendors extends Controller
{
    use Contacts;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $vendors = Contact::type($this->getVendorTypes())->collect();

        return view('purchases.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Contact  $vendor
     *
     * @return Response
     */
    public function show(Contact $vendor)
    {
        $amounts = [
            'paid' => 0,
            'open' => 0,
            'overdue' => 0,
        ];

        $counts = [];

        // Handle bills
        $bills = Bill::where('contact_id', $vendor->id)->get();

        $counts['bills'] = $bills->count();

        $today = Date::today()->toDateString();

        foreach ($bills as $item) {
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

        // Handle payments
        $transactions = Transaction::where('contact_id', $vendor->id)->type('expense')->get();

        $counts['transactions'] = $transactions->count();

        // Prepare data
        $transactions->each(function ($item) use (&$amounts) {
            $amounts['paid'] += $item->getAmountConvertedToDefault();
        });

        $limit = request('limit', setting('default.list_limit', '25'));
        $transactions = $this->paginate($transactions->sortByDesc('paid_at'), $limit);
        $bills = $this->paginate($bills->sortByDesc('paid_at'), $limit);

        return view('purchases.vendors.show', compact('vendor', 'counts', 'amounts', 'transactions', 'bills'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('purchases.vendors.create', compact('currencies'));
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
            $response['redirect'] = route('vendors.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.vendors', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('vendors.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Contact  $vendor
     *
     * @return Response
     */
    public function duplicate(Contact $vendor)
    {
        $clone = $vendor->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.vendors', 1)]);

        flash($message)->success();

        return redirect()->route('vendors.edit', $clone->id);
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

        $message = trans('messages.success.imported', ['type' => trans_choice('general.vendors', 2)]);

        flash($message)->success();

        return redirect()->route('vendors.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Contact  $vendor
     *
     * @return Response
     */
    public function edit(Contact $vendor)
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('purchases.vendors.edit', compact('vendor', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Contact $vendor
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Contact $vendor, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateContact($vendor, $request));

        if ($response['success']) {
            $response['redirect'] = route('vendors.index');

            $message = trans('messages.success.updated', ['type' => $vendor->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('vendors.edit', $vendor->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Contact $vendor
     *
     * @return Response
     */
    public function enable(Contact $vendor)
    {
        $response = $this->ajaxDispatch(new UpdateContact($vendor, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $vendor->name]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Contact $vendor
     *
     * @return Response
     */
    public function disable(Contact $vendor)
    {
        $response = $this->ajaxDispatch(new UpdateContact($vendor, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $vendor->name]);
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Contact $vendor
     *
     * @return Response
     */
    public function destroy(Contact $vendor)
    {
        $response = $this->ajaxDispatch(new DeleteContact($vendor));

        $response['redirect'] = route('vendors.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $vendor->name]);

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
        return \Excel::download(new Export(), \Str::filename(trans_choice('general.vendors', 2)) . '.xlsx');
    }

    public function currency(Contact $vendor)
    {
        if (empty($vendor)) {
            return response()->json([]);
        }

        $currency_code = setting('default.currency');

        if (isset($vendor->currency_code)) {
            $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

            if (array_key_exists($vendor->currency_code, $currencies)) {
                $currency_code = $vendor->currency_code;
            }
        }

        // Get currency object
        $currency = Currency::where('code', $currency_code)->first();

        $vendor->currency_name = $currency->name;
        $vendor->currency_code = $currency_code;
        $vendor->currency_rate = $currency->rate;

        $vendor->thousands_separator = $currency->thousands_separator;
        $vendor->decimal_mark = $currency->decimal_mark;
        $vendor->precision = (int) $currency->precision;
        $vendor->symbol_first = $currency->symbol_first;
        $vendor->symbol = $currency->symbol;

        return response()->json($vendor);
    }
}
