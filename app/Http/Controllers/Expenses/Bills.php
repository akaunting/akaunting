<?php

namespace App\Http\Controllers\Expenses;

use App\Abstracts\Http\Controller;
use App\Exports\Expenses\Bills as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Http\Requests\Expense\Bill as Request;
use App\Http\Requests\Expense\BillAddItem as ItemRequest;
use App\Imports\Expenses\Bills as Import;
use App\Jobs\Expense\CreateBill;
use App\Jobs\Expense\DeleteBill;
use App\Jobs\Expense\DuplicateBill;
use App\Jobs\Expense\UpdateBill;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Expense\BillStatus;
use App\Models\Expense\Bill;
use App\Models\Expense\BillHistory;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Contacts;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Uploads;
use App\Utilities\Modules;

class Bills extends Controller
{
    use Contacts, Currencies, DateTime, Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $bills = Bill::with(['contact', 'items', 'histories', 'status', 'transactions'])->collect(['billed_at'=> 'desc']);

        $vendors = Contact::type($this->getVendorTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::type('expense')->enabled()->orderBy('name')->pluck('name', 'id');

        $statuses = collect(BillStatus::get()->each(function ($item) {
            $item->name = trans('bills.status.' . $item->code);
            return $item;
        })->pluck('name', 'code'));

        return view('expenses.bills.index', compact('bills', 'vendors', 'categories', 'statuses'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Bill  $bill
     *
     * @return Response
     */
    public function show(Bill $bill)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $bill->currency_code)->first();

        $account_currency_code = Account::where('id', setting('default.account'))->pluck('currency_code')->first();

        $vendors = Contact::type($this->getVendorTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::type('expense')->enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $date_format = $this->getCompanyDateFormat();

        return view('expenses.bills.show', compact('bill', 'accounts', 'currencies', 'currency', 'account_currency_code', 'vendors', 'categories', 'payment_methods', 'date_format'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $vendors = Contact::type($this->getVendorTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', setting('default.currency'))->first();

        $items = Item::enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $categories = Category::type('expense')->enabled()->orderBy('name')->pluck('name', 'id');

        return view('expenses.bills.create', compact('vendors', 'currencies', 'currency', 'items', 'taxes', 'categories'));
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
        $response = $this->ajaxDispatch(new CreateBill($request));

        if ($response['success']) {
            $response['redirect'] = route('bills.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.bills', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('bills.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Bill $bill
     *
     * @return Response
     */
    public function duplicate(Bill $bill)
    {
        $clone = $this->dispatch(new DuplicateBill($bill));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.bills', 1)]);

        flash($message)->success();

        return redirect()->route('bills.edit', $clone->id);
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
        $success = true;

        \Excel::import(new Import(), $request->file('import'));

        if (!$success) {
            return redirect()->route('import.create', ['expenses', 'bills']);
        }

        $message = trans('messages.success.imported', ['type' => trans_choice('general.bills', 2)]);

        flash($message)->success();

        return redirect()->route('bills.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Bill  $bill
     *
     * @return Response
     */
    public function edit(Bill $bill)
    {
        $vendors = Contact::type($this->getVendorTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $bill->currency_code)->first();

        $items = Item::enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $categories = Category::type('expense')->enabled()->orderBy('name')->pluck('name', 'id');

        return view('expenses.bills.edit', compact('bill', 'vendors', 'currencies', 'currency', 'items', 'taxes', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Bill $bill
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Bill $bill, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateBill($bill, $request));

        if ($response['success']) {
            $response['redirect'] = route('bills.index');

            $message = trans('messages.success.updated', ['type' => trans_choice('general.bills', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('bills.edit', $bill->id);

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
    public function destroy(Bill $bill)
    {
        $response = $this->ajaxDispatch(new DeleteBill($bill));

        $response['redirect'] = route('bills.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.bills', 1)]);

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
        return \Excel::download(new Export(), trans_choice('general.bills', 2) . '.xlsx');
    }

    /**
     * Mark the bill as received.
     *
     * @param  Bill $bill
     *
     * @return Response
     */
    public function markReceived(Bill $bill)
    {
        $bill->bill_status_code = 'received';
        $bill->save();

        // Add bill history
        BillHistory::create([
            'company_id' => $bill->company_id,
            'bill_id' => $bill->id,
            'status_code' => 'received',
            'notify' => 0,
            'description' => trans('bills.mark_received'),
        ]);

        $message = trans('bills.messages.received');

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Print the bill.
     *
     * @param  Bill $bill
     *
     * @return Response
     */
    public function printBill(Bill $bill)
    {
        $bill = $this->prepareBill($bill);

        return view($bill->template_path, compact('bill'));
    }

    /**
     * Download the PDF file of bill.
     *
     * @param  Bill $bill
     *
     * @return Response
     */
    public function pdfBill(Bill $bill)
    {
        $bill = $this->prepareBill($bill);

        $currency_style = true;

        $view = view($bill->template_path, compact('bill', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = 'bill_' . time() . '.pdf';

        return $pdf->download($file_name);
    }

    public function addItem(ItemRequest $request)
    {
        $item_row = $request['item_row'];
        $currency_code = $request['currency_code'];

        $taxes = Tax::enabled()->orderBy('rate')->get()->pluck('title', 'id');

        $currency = Currency::where('code', '=', $currency_code)->first();

        if (empty($currency)) {
            $currency = Currency::where('code', '=', setting('default.currency'))->first();
        }

        if ($currency) {
            // it should be integer for amount mask
            $currency->precision = (int) $currency->precision;
        }

        $html = view('expenses.bills.item', compact('item_row', 'taxes', 'currency'))->render();

        return response()->json([
            'success' => true,
            'error'   => false,
            'data'    => [
                'currency' => $currency
            ],
            'message' => 'null',
            'html'    => $html,
        ]);
    }

    protected function prepareBill(Bill $bill)
    {
        $paid = 0;

        foreach ($bill->payments as $item) {
            $amount = $item->amount;

            if ($bill->currency_code != $item->currency_code) {
                $item->default_currency_code = $bill->currency_code;

                $amount = $item->getAmountConvertedFromCustomDefault();
            }

            $paid += $amount;
        }

        $bill->paid = $paid;

        $bill->template_path = 'expenses.bills.bill';

        //event(new BillPrinting($bill));

        return $bill;
    }
}
