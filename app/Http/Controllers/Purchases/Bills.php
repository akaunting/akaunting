<?php

namespace App\Http\Controllers\Purchases;

use App\Abstracts\Http\Controller;
use App\Exports\Purchases\Bills as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Http\Requests\Purchase\Bill as Request;
use App\Http\Requests\Purchase\BillAddItem as ItemRequest;
use App\Imports\Purchases\Bills as Import;
use App\Jobs\Banking\CreateDocumentTransaction;
use App\Jobs\Purchase\CreateBill;
use App\Jobs\Purchase\DeleteBill;
use App\Jobs\Purchase\DuplicateBill;
use App\Jobs\Purchase\UpdateBill;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Purchase\Bill;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Purchases;
use App\Traits\Uploads;
use App\Utilities\Modules;

class Bills extends Controller
{
    use Currencies, DateTime, Purchases, Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $bills = Bill::with(['contact', 'items', 'histories', 'transactions'])->collect(['billed_at'=> 'desc']);

        $vendors = Contact::vendor()->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::expense()->enabled()->orderBy('name')->pluck('name', 'id');

        $statuses = $this->getBillStatuses();

        return view('purchases.bills.index', compact('bills', 'vendors', 'categories', 'statuses'));
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

        $vendors = Contact::vendor()->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::expense()->enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $date_format = $this->getCompanyDateFormat();

        // Get Bill Totals
        foreach ($bill->totals_sorted as $bill_total) {
            $bill->{$bill_total->code} = $bill_total->amount;
        }

        $total = money($bill->total, $currency->code, true)->format();

        $bill->grand_total = money($total, $currency->code)->getAmount();

        if (!empty($bill->paid)) {
            $bill->grand_total = round($bill->total - $bill->paid, $currency->precision) ;
        }

        return view('purchases.bills.show', compact('bill', 'accounts', 'currencies', 'currency', 'account_currency_code', 'vendors', 'categories', 'payment_methods', 'date_format'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $vendors = Contact::vendor()->enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', setting('default.currency'))->first();

        $items = Item::enabled()->orderBy('name')->get();

        $taxes = Tax::enabled()->orderBy('name')->get();

        $categories = Category::expense()->enabled()->orderBy('name')->pluck('name', 'id');

        $number = $this->getNextBillNumber();

        return view('purchases.bills.create', compact('vendors', 'currencies', 'currency', 'items', 'taxes', 'categories', 'number'));
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
            $response['redirect'] = route('bills.show', $response['data']->id);

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
        try {
            \Excel::import(new Import(), $request->file('import'));
        } catch (\Maatwebsite\Excel\Exceptions\SheetNotFoundException $e) {
            flash($e->getMessage())->error()->important();

            return redirect()->route('import.create', ['purchases', 'bills']);
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
        $vendors = Contact::vendor()->enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $bill->currency_code)->first();

        $items = Item::enabled()->orderBy('name')->get();

        $taxes = Tax::enabled()->orderBy('name')->get();

        $categories = Category::expense()->enabled()->orderBy('name')->pluck('name', 'id');

        return view('purchases.bills.edit', compact('bill', 'vendors', 'currencies', 'currency', 'items', 'taxes', 'categories'));
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
            $response['redirect'] = route('bills.show', $response['data']->id);

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
     * @param  $bill
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
        return \Excel::download(new Export(), \Str::filename(trans_choice('general.bills', 2)) . '.xlsx');
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
        event(new \App\Events\Purchase\BillReceived($bill));

        $message = trans('bills.messages.marked_received');

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Mark the bill as cancelled.
     *
     * @param  Bill $bill
     *
     * @return Response
     */
    public function markCancelled(Bill $bill)
    {
        event(new \App\Events\Purchase\BillCancelled($bill));

        $message = trans('bills.messages.marked_cancelled');

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

        $view = view($bill->template_path, compact('bill'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
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
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $this->getBillFileName($bill);

        return $pdf->download($file_name);
    }

    /**
     * Mark the bill as paid.
     *
     * @param  Bill $bill
     *
     * @return Response
     */
    public function markPaid(Bill $bill)
    {
        try {
            $this->dispatch(new CreateDocumentTransaction($bill, []));

            $message = trans('bills.messages.marked_paid');

            flash($message)->success();
        } catch(\Exception $e) {
            $message = $e->getMessage();

            flash($message)->error();
        }

        return redirect()->back();
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

        $html = view('purchases.bills.item', compact('item_row', 'taxes', 'currency'))->render();

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

        foreach ($bill->transactions as $item) {
            $amount = $item->amount;

            if ($bill->currency_code != $item->currency_code) {
                $item->default_currency_code = $bill->currency_code;

                $amount = $item->getAmountConvertedFromDefault();
            }

            $paid += $amount;
        }

        $bill->paid = $paid;

        $bill->template_path = 'purchases.bills.print';

        return $bill;
    }
}
