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
use App\Models\Expense\BillItem;
use App\Models\Expense\BillTotal;
use App\Models\Expense\BillHistory;
use App\Models\Expense\BillPayment;
use App\Models\Item\Item;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Uploads;
use App\Utilities\ImportFile;
use App\Utilities\Modules;
use Date;

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
        $bills = Bill::with(['vendor', 'status', 'items', 'payments', 'histories'])->collect(['billed_at'=> 'desc']);

        $vendors = collect(Vendor::enabled()->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.vendors', 2)]), '');

        $statuses = collect(BillStatus::all()->pluck('name', 'code'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.statuses', 2)]), '');

        return view('expenses.bills.index', compact('bills', 'vendors', 'statuses'));
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
        $paid = 0;

        foreach ($bill->payments as $item) {
            $item->default_currency_code = $bill->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $bill->paid = $paid;

        $accounts = Account::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('general.default_account'))->pluck('currency_code')->first();

        $vendors = Vendor::enabled()->pluck('name', 'id');

        $categories = Category::enabled()->type('income')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('expenses.bills.show', compact('bill', 'accounts', 'currencies', 'account_currency_code', 'vendors', 'categories', 'payment_methods'));
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

        $request['bill_status_code'] = 'draft';

        $request['amount'] = 0;

        $bill = Bill::create($request->input());

        // Upload attachment
        if ($request->file('attachment')) {
            $media = $this->getMedia($request->file('attachment'), 'bills');

            $bill->attachMedia($media, 'attachment');
        }

        $taxes = [];

        $tax_total = 0;
        $sub_total = 0;

        $bill_item = [];
        $bill_item['company_id'] = $request['company_id'];
        $bill_item['bill_id'] = $bill->id;

        if ($request['item']) {
            foreach ($request['item'] as $item) {
                unset($tax_object);
                $item_sku = '';

                if (!empty($item['item_id'])) {
                    $item_object = Item::find($item['item_id']);

                    $item_sku = $item_object->sku;

                    // Increase stock (item bought)
                    $item_object->quantity += $item['quantity'];
                    $item_object->save();
                }

                $tax = $tax_id = 0;

                if (!empty($item['tax_id'])) {
                    $tax_object = Tax::find($item['tax_id']);

                    $tax_id = $item['tax_id'];

                    $tax = (($item['price'] * $item['quantity']) / 100) * $tax_object->rate;
                }

                $bill_item['item_id'] = $item['item_id'];
                $bill_item['name'] = str_limit($item['name'], 180, '');
                $bill_item['sku'] = $item_sku;
                $bill_item['quantity'] = $item['quantity'];
                $bill_item['price'] = $item['price'];
                $bill_item['tax'] = $tax;
                $bill_item['tax_id'] = $tax_id;
                $bill_item['total'] = $item['price'] * $item['quantity'];

                if (isset($tax_object)) {
                    if (array_key_exists($tax_object->id, $taxes)) {
                        $taxes[$tax_object->id]['amount'] += $tax;
                    } else {
                        $taxes[$tax_object->id] = [
                            'name' => $tax_object->name,
                            'amount' => $tax
                        ];
                    }
                }

                $tax_total += $tax;
                $sub_total += $bill_item['total'];

                BillItem::create($bill_item);
            }
        }

        $request['amount'] += $sub_total + $tax_total;

        $bill->update($request->input());

        // Added bill total sub total
        BillTotal::create([
            'company_id' => $request['company_id'],
            'bill_id' => $bill->id,
            'code' => 'sub_total',
            'name' => 'bills.sub_total',
            'amount' => $sub_total,
            'sort_order' => 1,
        ]);

        $sort_order = 2;

        // Added bill total taxes
        if ($taxes) {
            foreach ($taxes as $tax) {
                BillTotal::create([
                    'company_id' => $request['company_id'],
                    'bill_id' => $bill->id,
                    'code' => 'tax',
                    'name' => $tax['name'],
                    'amount' => $tax['amount'],
                    'sort_order' => $sort_order,
                ]);

                $sort_order++;
            }
        }

        // Added bill total total
        BillTotal::create([
            'company_id' => $request['company_id'],
            'bill_id' => $bill->id,
            'code' => 'total',
            'name' => 'bills.total',
            'amount' => $sub_total + $tax_total,
            'sort_order' => $sort_order,
        ]);

        // Add bill history
        BillHistory::create([
            'company_id' => session('company_id'),
            'bill_id' => $bill->id,
            'status_code' => 'draft',
            'notify' => 0,
            'description' => trans('messages.success.added', ['type' => $bill->bill_number]),
        ]);

        // Fire the event to make it extendible
        event(new BillCreated($bill));

        $message = trans('messages.success.added', ['type' => trans_choice('general.bills', 1)]);

        flash($message)->success();

        return redirect('expenses/bills/' . $bill->id);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Bill  $bill
     *
     * @return Response
     */
    public function duplicate(Bill $bill)
    {
        $clone = $bill->duplicate();

        // Add bill history
        BillHistory::create([
            'company_id' => session('company_id'),
            'bill_id' => $clone->id,
            'status_code' => 'draft',
            'notify' => 0,
            'description' => trans('messages.success.added', ['type' => $clone->bill_number]),
        ]);

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.bills', 1)]);

        flash($message)->success();

        return redirect('expenses/bills/' . $clone->id . '/edit');
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
        $rows = $import->all();

        foreach ($rows as $row) {
            $data = $row->toArray();
            $data['company_id'] = session('company_id');

            Bill::create($data);
        }

        $message = trans('messages.success.imported', ['type' => trans_choice('general.bills', 2)]);

        flash($message)->success();

        return redirect('expenses/bills');
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

        $request['amount'] = 0;

        $taxes = [];
        $tax_total = 0;
        $sub_total = 0;

        $bill_item = [];
        $bill_item['company_id'] = $request['company_id'];
        $bill_item['bill_id'] = $bill->id;

        if ($request['item']) {
            BillItem::where('bill_id', $bill->id)->delete();

            foreach ($request['item'] as $item) {
                unset($tax_object);
                $item_sku = '';

                if (!empty($item['item_id'])) {
                    $item_object = Item::find($item['item_id']);

                    $item_sku = $item_object->sku;
                }

                $tax = $tax_id = 0;

                if (!empty($item['tax_id'])) {
                    $tax_object = Tax::find($item['tax_id']);

                    $tax_id = $item['tax_id'];

                    $tax = (($item['price'] * $item['quantity']) / 100) * $tax_object->rate;
                }

                $bill_item['item_id'] = $item['item_id'];
                $bill_item['name'] = str_limit($item['name'], 180, '');
                $bill_item['sku'] = $item_sku;
                $bill_item['quantity'] = $item['quantity'];
                $bill_item['price'] = $item['price'];
                $bill_item['tax'] = $tax;
                $bill_item['tax_id'] = $tax_id;
                $bill_item['total'] = $item['price'] * $item['quantity'];

                if (isset($tax_object)) {
                    if (array_key_exists($tax_object->id, $taxes)) {
                        $taxes[$tax_object->id]['amount'] += $tax;
                    } else {
                        $taxes[$tax_object->id] = [
                            'name' => $tax_object->name,
                            'amount' => $tax
                        ];
                    }
                }

                $tax_total += $tax;
                $sub_total += $bill_item['total'];

                BillItem::create($bill_item);
            }
        }

        BillTotal::where('bill_id', $bill->id)->delete();

        // Added bill total sub total
        $bill_sub_total = [
            'company_id' => $request['company_id'],
            'bill_id' => $bill->id,
            'code' => 'sub_total',
            'name' => 'bills.sub_total',
            'amount' => $sub_total,
            'sort_order' => 1,
        ];

        BillTotal::create($bill_sub_total);

        $sort_order = 2;

        // Added bill total taxes
        if ($taxes) {
            foreach ($taxes as $tax) {
                $bill_tax_total = [
                    'company_id' => $request['company_id'],
                    'bill_id' => $bill->id,
                    'code' => 'tax',
                    'name' => $tax['name'],
                    'amount' => $tax['amount'],
                    'sort_order' => $sort_order,
                ];

                BillTotal::create($bill_tax_total);

                $sort_order++;
            }
        }

        $request['amount'] += $sub_total + $tax_total;

        $bill->update($request->input());

        // Upload attachment
        if ($request->file('attachment')) {
            $media = $this->getMedia($request->file('attachment'), 'bills');

            $bill->attachMedia($media, 'attachment');
        }

        // Added bill total total
        $bill_total = [
            'company_id' => $request['company_id'],
            'bill_id' => $bill->id,
            'code' => 'total',
            'name' => 'bills.total',
            'amount' => $sub_total + $tax_total,
            'sort_order' => $sort_order,
        ];

        BillTotal::create($bill_total);

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
        BillTotal::where('bill_id', $bill->id)->delete();
        BillPayment::where('bill_id', $bill->id)->delete();
        BillHistory::where('bill_id', $bill->id)->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.bills', 1)]);

        flash($message)->success();

        return redirect('expenses/bills');
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

        flash(trans('bills.messages.received'))->success();

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
        $paid = 0;

        foreach ($bill->payments as $item) {
            $item->default_currency_code = $bill->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $bill->paid = $paid;

        return view('expenses.bills.bill', compact('bill'));
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
        $paid = 0;

        foreach ($bill->payments as $item) {
            $item->default_currency_code = $bill->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $bill->paid = $paid;

        $html = view('expenses.bills.bill', compact('bill'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = 'bill_'.time().'.pdf';

        return $pdf->download($file_name);
    }

    /**
     * Add payment to the bill.
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

        $bill = Bill::find($request['bill_id']);

        $total_amount = $bill->amount;

        $amount = (double) $request['amount'];

        if ($request['currency_code'] != $bill->currency_code) {
            $request_bill = new Bill();

            $request_bill->amount = (float) $request['amount'];
            $request_bill->currency_code = $currency->code;
            $request_bill->currency_rate = $currency->rate;

            $amount = $request_bill->getConvertedAmount();
        }

        if ($bill->payments()->count()) {
            $total_amount -= $bill->payments()->paid();
        }

        if ($amount > $total_amount) {
            $message = trans('messages.error.payment_add');

            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $message,
            ]);
        } elseif ($amount == $total_amount) {
            $bill->bill_status_code = 'paid';
        } else {
            $bill->bill_status_code = 'partial';
        }

        $bill->save();

        $bill_payment = BillPayment::create($request->input());

        // Upload attachment
        if ($request->file('attachment')) {
            $media = $this->getMedia($request->file('attachment'), 'bills');

            $bill_payment->attachMedia($media, 'attachment');
        }

        $request['status_code'] = $bill->bill_status_code;
        $request['notify'] = 0;

        $desc_amount = money((float) $request['amount'], (string) $request['currency_code'], true)->format();

        $request['description'] = $desc_amount . ' ' . trans_choice('general.payments', 1);

        BillHistory::create($request->input());

        $message = trans('messages.success.added', ['type' => trans_choice('general.revenues', 1)]);

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => $message,
        ]);
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
        $bill = Bill::find($payment->bill_id);

        if ($bill->payments()->count() > 1) {
            $bill->bill_status_code = 'partial';
        } else {
            $bill->bill_status_code = 'draft';
        }

        $bill->save();

        $payment->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.bills', 1)]);

        flash($message)->success();

        return redirect()->back();
    }
}
