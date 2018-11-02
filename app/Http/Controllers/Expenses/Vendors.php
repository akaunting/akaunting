<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\Vendor as Request;
use App\Models\Expense\Bill;
use App\Models\Expense\Payment;
use App\Models\Expense\Vendor;
use App\Models\Setting\Currency;
use App\Traits\Uploads;
use App\Utilities\Import;
use App\Utilities\ImportFile;
use Date;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Vendors extends Controller
{
    use Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $vendors = Vendor::collect();

        return view('expenses.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Vendor  $vendor
     *
     * @return Response
     */
    public function show(Vendor $vendor)
    {
        $amounts = [
            'paid' => 0,
            'open' => 0,
            'overdue' => 0,
        ];

        $counts = [
            'bills' => 0,
            'payments' => 0,
        ];

        // Handle bills
        $bills = Bill::with(['status', 'payments'])->where('vendor_id', $vendor->id)->get();

        $counts['bills'] = $bills->count();

        $bill_payments = [];

        $today = Date::today()->toDateString();

        foreach ($bills as $item) {
            $payments = 0;

            foreach ($item->payments as $payment) {
                $payment->category = $item->category;

                $bill_payments[] = $payment;

                $amount = $payment->getConvertedAmount();

                $amounts['paid'] += $amount;

                $payments += $amount;
            }

            if ($item->bill_status_code == 'paid') {
                continue;
            }

            // Check if it's open or overdue invoice
            if ($item->due_at > $today) {
                $amounts['open'] += $item->getConvertedAmount() - $payments;
            } else {
                $amounts['overdue'] += $item->getConvertedAmount() - $payments;
            }
        }

        // Handle payments
        $payments = Payment::with(['account', 'category'])->where('vendor_id', $vendor->id)->get();

        $counts['payments'] = $payments->count();

        // Prepare data
        $items = collect($payments)->each(function ($item) use (&$amounts) {
            $amounts['paid'] += $item->getConvertedAmount();
        });

        $limit = request('limit', setting('general.list_limit', '25'));
        $transactions = $this->paginate($items->merge($bill_payments)->sortByDesc('paid_at'), $limit);
        $bills = $this->paginate($bills->sortByDesc('paid_at'), $limit);
        $payments = $this->paginate($payments->sortByDesc('paid_at'), $limit);

        return view('expenses.vendors.show', compact('vendor', 'counts', 'amounts', 'transactions', 'bills', 'payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('expenses.vendors.create', compact('currencies'));
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
        $vendor = Vendor::create($request->all());

        // Upload logo
        if ($request->file('logo')) {
            $media = $this->getMedia($request->file('logo'), 'vendors');

            $vendor->attachMedia($media, 'logo');
        }

        $message = trans('messages.success.added', ['type' => trans_choice('general.vendors', 1)]);

        flash($message)->success();

        return redirect('expenses/vendors');
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Vendor  $vendor
     *
     * @return Response
     */
    public function duplicate(Vendor $vendor)
    {
        $clone = $vendor->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.vendors', 1)]);

        flash($message)->success();

        return redirect('expenses/vendors/' . $clone->id . '/edit');
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportFile  $import
     *
     * @return Response
     */
    public function import(ImportFile $import)
    {
        if (!Import::createFromFile($import, 'Expense\Vendor')) {
            return redirect('common/import/expenses/vendors');
        }

        $message = trans('messages.success.imported', ['type' => trans_choice('general.vendors', 2)]);

        flash($message)->success();

        return redirect('expenses/vendors');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Vendor  $vendor
     *
     * @return Response
     */
    public function edit(Vendor $vendor)
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        return view('expenses.vendors.edit', compact('vendor', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Vendor  $vendor
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Vendor $vendor, Request $request)
    {
        $vendor->update($request->all());

        // Upload logo
        if ($request->file('logo')) {
            $media = $this->getMedia($request->file('logo'), 'vendors');

            $vendor->attachMedia($media, 'logo');
        }

        $message = trans('messages.success.updated', ['type' => trans_choice('general.vendors', 1)]);

        flash($message)->success();

        return redirect('expenses/vendors');
    }

    /**
     * Enable the specified resource.
     *
     * @param  Vendor  $vendor
     *
     * @return Response
     */
    public function enable(Vendor $vendor)
    {
        $vendor->enabled = 1;
        $vendor->save();

        $message = trans('messages.success.enabled', ['type' => trans_choice('general.vendors', 1)]);

        flash($message)->success();

        return redirect()->route('vendors.index');
    }

    /**
     * Disable the specified resource.
     *
     * @param  Vendor  $vendor
     *
     * @return Response
     */
    public function disable(Vendor $vendor)
    {
        $vendor->enabled = 0;
        $vendor->save();

        $message = trans('messages.success.disabled', ['type' => trans_choice('general.vendors', 1)]);

        flash($message)->success();

        return redirect()->route('vendors.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Vendor  $vendor
     *
     * @return Response
     */
    public function destroy(Vendor $vendor)
    {
        $relationships = $this->countRelationships($vendor, [
            'bills' => 'bills',
            'payments' => 'payments',
        ]);

        if (empty($relationships)) {
            $vendor->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('general.vendors', 1)]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.deleted', ['name' => $vendor->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();
        }

        return redirect('expenses/vendors');
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        \Excel::create('vendors', function($excel) {
            $excel->sheet('vendors', function($sheet) {
                $sheet->fromModel(Vendor::filter(request()->input())->get()->makeHidden([
                    'id', 'company_id', 'created_at', 'updated_at', 'deleted_at'
                ]));
            });
        })->download('xlsx');
    }

    public function currency()
    {
        $vendor_id = (int) request('vendor_id');

        if (empty($vendor_id)) {
            return response()->json([]);
        }

        $vendor = Vendor::find($vendor_id);

        if (empty($vendor)) {
            return response()->json([]);
        }

        $currency_code = setting('general.default_currency');

        if (isset($vendor->currency_code)) {
            $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

            if (array_key_exists($vendor->currency_code, $currencies)) {
                $currency_code = $vendor->currency_code;
            }
        }

        // Get currency object
        $currency = Currency::where('code', $currency_code)->first();

        $vendor->currency_code = $currency_code;
        $vendor->currency_rate = $currency->rate;

        $vendor->thousands_separator = $currency->thousands_separator;
        $vendor->decimal_mark = $currency->decimal_mark;
        $vendor->precision = (int) $currency->precision;
        $vendor->symbol_first = $currency->symbol_first;
        $vendor->symbol = $currency->symbol;

        return response()->json($vendor);
    }

    public function vendor(Request $request)
    {
        $vendor = Vendor::create($request->all());

        return response()->json($vendor);
    }

    /**
     * Generate a pagination collection.
     *
     * @param array|Collection      $items
     * @param int   $perPage
     * @param int   $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
