<?php

namespace App\Http\Controllers\Expenses;

use App\Events\BillCreated;
use App\Events\BillUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\Bill as Request;
use App\Http\Requests\Expense\BillPayment as PaymentRequest;
use App\Models\Banking\Account;
use App\Models\Expense\BillStatus;
use App\Models\Expense\Vendor;
use App\Models\Expense\Bill;
use App\Models\Expense\BillHistory;
use App\Models\Expense\BillItem;
use App\Models\Expense\BillPayment;
use App\Models\Item\Item;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Uploads;
use Jenssegers\Date\Date;

use App\Utilities\Modules;

class Bills extends Controller
{
    use DateTime, Currencies, Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $bills = Bill::with('status')->collect();

        $vendors = collect(Vendor::enabled()->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.vendors', 2)]), '');

        $status = collect(BillStatus::all()->pluck('name', 'code'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.statuses', 2)]), '');

        return view('expenses.bills.index', compact('bills', 'vendors', 'status'));
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
        $sub_total = 0;
        $tax_total = 0;
        $paid = 0;

        foreach ($bill->items as $item) {
            $sub_total += ($item->price * $item->quantity);
            $tax_total += ($item->tax * $item->quantity);
        }

        foreach ($bill->payments as $item) {
            $item->default_currency_code = $bill->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $bill->sub_total = $sub_total;
        $bill->tax_total = $tax_total;
        $bill->paid = $paid;
        $bill->grand_total = (($sub_total + $tax_total) - $paid);

        $accounts = Account::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('general.default_account'))->pluck('currency_code')->first();

        $vendors = Vendor::enabled()->pluck('name', 'id');

        $categories = Category::enabled()->type('income')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('expenses.bills.show', compact('bill', 'accounts', 'currencies', 'account_currency_code', 'vendors', 'categories', 'payment_methods'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  int  $bill_id
     *
     * @return Response
     */
    public function printBill($bill_id)
    {
        $sub_total = 0;
        $tax_total = 0;
        $paid = 0;

        $bill = Bill::where('id', $bill_id)->first();

        foreach ($bill->items as $item) {
            $sub_total += ($item->price * $item->quantity);
            $tax_total += ($item->tax * $item->quantity);
        }

        foreach ($bill->payments as $item) {
            $item->default_currency_code = $bill->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $bill->sub_total = $sub_total;
        $bill->tax_total = $tax_total;
        $bill->paid = $paid;
        $bill->grand_total = (($sub_total + $tax_total) - $paid);

        return view('expenses.bills.bill', compact('bill'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  int  $bill_id
     *
     * @return Response
     */
    public function pdfBill($bill_id)
    {
        $sub_total = 0;
        $tax_total = 0;
        $paid = 0;

        $bill = Bill::where('id', $bill_id)->first();

        foreach ($bill->items as $item) {
            $sub_total += ($item->price * $item->quantity);
            $tax_total += ($item->tax * $item->quantity);
        }

        foreach ($bill->payments as $item) {
            $item->default_currency_code = $bill->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $bill->sub_total = $sub_total;
        $bill->tax_total = $tax_total;
        $bill->paid = $paid;
        $bill->grand_total = (($sub_total + $tax_total) - $paid);

        $html = view('expenses.bills.bill', compact('bill'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = 'bill_'.time().'.pdf';

        return $pdf->download($file_name);
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  PaymentRequest  $request
     *
     * @return Response
     */
    public function payment(PaymentRequest $request)
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

        $bill = Bill::find($request['bill_id']);

        if ($request['currency_code'] == $bill->currency_code) {
            if ($request['amount'] > $bill->amount) {
                $message = trans('messages.error.added', ['type' => trans_choice('general.payment', 1)]);

                return response()->json($message);
            } elseif ($request['amount'] == $bill->amount) {
                $bill->bill_status_code = 'paid';
            } else {
                $bill->bill_status_code = 'partial';
            }
        } else {
            $request_bill = new Bill();

            $request_bill->amount = (float) $request['amount'];
            $request_bill->currency_code = $currency->code;
            $request_bill->currency_rate = $currency->rate;

            $amount = $request_bill->getConvertedAmount();

            if ($amount > $bill->amount) {
                $message = trans('messages.error.added', ['type' => trans_choice('general.payment', 1)]);

                return response()->json($message);
            } elseif ($amount == $bill->amount) {
                $bill->bill_status_code = 'paid';
            } else {
                $bill->bill_status_code = 'partial';
            }
        }

        $bill->save();

        $bill_payment = BillPayment::create($request->input());

        $request['status_code'] = $bill->bill_status_code;
        $request['notify'] = 0;
        
        $desc_date = Date::parse($request['paid_at'])->format($this->getCompanyDateFormat());
        $desc_amount = money((float) $request['amount'], $request['currency_code'], true)->format();
        $request['description'] = $desc_date . ' ' . $desc_amount;

        BillHistory::create($request->input());

        $message = trans('messages.success.added', ['type' => trans_choice('general.revenues', 1)]);

        return response()->json($message);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $vendors = Vendor::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code');

        $items = Item::enabled()->pluck('name', 'id');

        $taxes = Tax::enabled()->pluck('name', 'id');

        return view('expenses.bills.create', compact('vendors', 'currencies', 'items', 'taxes'));
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
        // Get vendor object
        $vendor = Vendor::findOrFail($request['vendor_id']);

        $request['vendor_name'] = $vendor->name;
        $request['vendor_email'] = $vendor->email;
        $request['vendor_tax_number'] = $vendor->tax_number;
        $request['vendor_phone'] = $vendor->phone;
        $request['vendor_address'] = $vendor->address;

        // Get currency object
        $currency = Currency::where('code', $request['currency_code'])->first();

        $request['currency_code'] = $currency->code;
        $request['currency_rate'] = $currency->rate;

        $request['bill_status_code'] = 'new';

        $request['amount'] = 0;

        // Upload attachment
        $attachment_path = $this->getUploadedFilePath($request->file('attachment'), 'bills');
        if ($attachment_path) {
            $request['attachment'] = $attachment_path;
        }

        $bill = Bill::create($request->input());

        $bill_item = array();
        $bill_item['company_id'] = $request['company_id'];
        $bill_item['bill_id'] = $bill->id;

        if ($request['item']) {
            foreach ($request['item'] as $item) {
                $item_sku = '';

                if (!empty($item['item_id'])) {
                    $data = Item::where('id', $item['item_id'])->first();

                    $item_sku = $data['sku'];
                }

                $tax_id = 0;
                $tax_rate = 0;

                if (!empty($item['tax'])) {
                    $tax = Tax::where('id', $item['tax'])->first();

                    $tax_rate = $tax->rate;
                    $tax_id = $item['tax'];
                }

                $bill_item['item_id'] = $item['item_id'];
                $bill_item['name'] = $item['name'];
                $bill_item['sku'] = $item_sku;
                $bill_item['quantity'] = $item['quantity'];
                $bill_item['price'] = $item['price'];
                $bill_item['tax'] = (($item['price'] * $item['quantity']) / 100) * $tax_rate;
                $bill_item['tax_id'] = $tax_id;
                $bill_item['total'] = ($item['price'] + $bill_item['tax']) * $item['quantity'];

                $request['amount'] += $bill_item['total'];

                BillItem::create($bill_item);
            }
        }

        $bill->update($request->input());

        $request['bill_id'] = $bill->id;
        $request['status_code'] = 'new';
        $request['notify'] = 0;
        $request['description'] = trans('messages.success.added', ['type' => $request['bill_number']]);

        BillHistory::create($request->input());

        // Fire the event to make it extendible
        event(new BillCreated($bill));

        $message = trans('messages.success.added', ['type' => trans_choice('general.bills', 1)]);

        flash($message)->success();

        return redirect('expenses/bills/' . $bill->id);
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
        $vendors = Vendor::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code');

        $items = Item::enabled()->pluck('name', 'id');

        $taxes = Tax::enabled()->pluck('name', 'id');

        return view('expenses.bills.edit', compact('bill', 'vendors', 'currencies', 'items', 'taxes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Bill  $bill
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Bill $bill, Request $request)
    {
        // Get vendor object
        $vendor = Vendor::findOrFail($request['vendor_id']);

        $request['vendor_name'] = $vendor->name;
        $request['vendor_email'] = $vendor->email;
        $request['vendor_tax_number'] = $vendor->tax_number;
        $request['vendor_phone'] = $vendor->phone;
        $request['vendor_address'] = $vendor->address;

        // Get currency object
        $currency = Currency::where('code', $request['currency_code'])->first();

        $request['currency_code'] = $currency->code;
        $request['currency_rate'] = $currency->rate;

        $request['bill_status_code'] = 'updated';

        $request['amount'] = 0;

        // Upload attachment
        $attachment_path = $this->getUploadedFilePath($request->file('attachment'), 'bills');
        if ($attachment_path) {
            $request['attachment'] = $attachment_path;
        }

        $bill_item = array();
        $bill_item['company_id'] = $request['company_id'];
        $bill_item['bill_id'] = $bill->id;

        if ($request['item']) {
            BillItem::where('bill_id', $bill->id)->delete();

            foreach ($request['item'] as $item) {
                $item_sku = '';

                if (!empty($item['item_id'])) {
                    $data = Item::where('id', $item['item_id'])->first();

                    $item_sku = $data['sku'];
                }

                $tax_id = 0;
                $tax_rate = 0;

                if (!empty($item['tax'])) {
                    $tax = Tax::where('id', $item['tax'])->first();

                    $tax_rate = $tax->rate;
                    $tax_id = $item['tax'];
                }

                $bill_item['item_id'] = $item['item_id'];
                $bill_item['name'] = $item['name'];
                $bill_item['sku'] = $item_sku;
                $bill_item['quantity'] = $item['quantity'];
                $bill_item['price'] = $item['price'];
                $bill_item['tax'] = (($item['price'] * $item['quantity']) / 100) * $tax_rate;
                $bill_item['tax_id'] = $tax_id;
                $bill_item['total'] = ($item['price'] + $bill_item['tax']) * $item['quantity'];

                $request['amount'] += $bill_item['total'];

                BillItem::create($bill_item);
            }
        }

        $bill->update($request->input());

        // Fire the event to make it extendible
        event(new BillUpdated($bill));

        $message = trans('messages.success.updated', ['type' => trans_choice('general.bills', 1)]);

        flash($message)->success();

        return redirect('expenses/bills/' . $bill->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Bill  $bill
     *
     * @return Response
     */
    public function destroy(Bill $bill)
    {
        $bill->delete();

        /*
        $bill->items->delete();
        $bill->payments->delete();
        $bill->histories->delete();
        */

        BillItem::where('bill_id', $bill->id)->delete();
        BillPayment::where('bill_id', $bill->id)->delete();
        BillHistory::where('bill_id', $bill->id)->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.bills', 1)]);

        flash($message)->success();

        return redirect('expenses/bills');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BillPayment  $payment
     *
     * @return Response
     */
    public function paymentDestroy(BillPayment $payment)
    {
        $payment->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.bills', 1)]);

        flash($message)->success();

        return redirect('expenses/bills');
    }
}
