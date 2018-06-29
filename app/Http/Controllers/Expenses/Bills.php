<?php

namespace App\Http\Controllers\Expenses;

use App\Events\BillCreated;
//use App\Events\BillPrinting;
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
use App\Models\Common\Item;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Models\Common\Media;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Uploads;
use App\Utilities\Import;
use App\Utilities\ImportFile;
use App\Utilities\Modules;
use Date;
use File;
use Image;
use Storage;

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

        $vendors = collect(Vendor::enabled()->orderBy('name')->pluck('name', 'id'))
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

        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('general.default_account'))->pluck('currency_code')->first();

        $vendors = Vendor::enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::enabled()->type('income')->orderBy('name')->pluck('name', 'id');

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
        $vendors = Vendor::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code');

        $items = Item::enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('rate')->get()->pluck('title', 'id');

        $categories = Category::enabled()->type('expense')->orderBy('name')->pluck('name', 'id');

        return view('expenses.bills.create', compact('vendors', 'currencies', 'items', 'taxes', 'categories'));
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
        $bill = Bill::create($request->input());

        // Upload attachment
        if ($request->file('attachment')) {
            $media = $this->getMedia($request->file('attachment'), 'bills');

            $bill->attachMedia($media, 'attachment');
        }

        $taxes = [];

        $tax_total = 0;
        $sub_total = 0;
        $discount_total = 0;
        $discount = $request['discount'];

        $bill_item = [];
        $bill_item['company_id'] = $request['company_id'];
        $bill_item['bill_id'] = $bill->id;

        if ($request['item']) {
            foreach ($request['item'] as $item) {
                unset($tax_object);
                $item_sku = '';

                if (!empty($item['item_id'])) {
                    $item_object = Item::find($item['item_id']);

                    $item['name'] = $item_object->name;
                    $item_sku = $item_object->sku;

                    // Increase stock (item bought)
                    $item_object->quantity += $item['quantity'];
                    $item_object->save();
                }

                $tax = $tax_id = 0;

                if (!empty($item['tax_id'])) {
                    $tax_object = Tax::find($item['tax_id']);

                    $tax_id = $item['tax_id'];

                    $tax = (((double) $item['price'] * (double) $item['quantity']) / 100) * $tax_object->rate;

                    // Apply discount to tax
                    if ($discount) {
                        $tax = $tax - ($tax * ($discount / 100));
                    }
                }

                $bill_item['item_id'] = $item['item_id'];
                $bill_item['name'] = str_limit($item['name'], 180, '');
                $bill_item['sku'] = $item_sku;
                $bill_item['quantity'] = (double) $item['quantity'];
                $bill_item['price'] = (double) $item['price'];
                $bill_item['tax'] = $tax;
                $bill_item['tax_id'] = $tax_id;
                $bill_item['total'] = (double) $item['price'] * (double) $item['quantity'];

                BillItem::create($bill_item);

                // Set taxes
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

                // Calculate totals
                $tax_total += $tax;
                $sub_total += $bill_item['total'];

                unset($tax_object);
            }
        }

        $s_total = $sub_total;

        // Apply discount to total
        if ($discount) {
            $s_discount = $s_total * ($discount / 100);
            $discount_total += $s_discount;
            $s_total = $s_total - $s_discount;
        }

        $request['amount'] = $s_total + $tax_total;

        $bill->update($request->input());

        // Add bill totals
        $this->addTotals($bill, $request, $taxes, $sub_total, $discount_total, $tax_total);

        // Add bill history
        BillHistory::create([
            'company_id' => session('company_id'),
            'bill_id' => $bill->id,
            'status_code' => 'draft',
            'notify' => 0,
            'description' => trans('messages.success.added', ['type' => $bill->bill_number]),
        ]);

        // Recurring
        $bill->createRecurring();

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
        $success = true;

        $allowed_sheets = ['bills', 'bill_items', 'bill_histories', 'bill_payments', 'bill_totals'];

        // Loop through all sheets
        $import->each(function ($sheet) use (&$success, $allowed_sheets) {
            $sheet_title = $sheet->getTitle();

            if (!in_array($sheet_title, $allowed_sheets)) {
                $message = trans('messages.error.import_sheet');

                flash($message)->error()->important();

                return false;
            }

            $slug = 'Expense\\' . str_singular(studly_case($sheet_title));

            if (!$success = Import::createFromSheet($sheet, $slug)) {
                return false;
            }
        });

        if (!$success) {
            return redirect('common/import/expenses/bills');
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
        $vendors = Vendor::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code');

        $items = Item::enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('rate')->get()->pluck('title', 'id');

        $categories = Category::enabled()->type('expense')->orderBy('name')->pluck('name', 'id');

        return view('expenses.bills.edit', compact('bill', 'vendors', 'currencies', 'items', 'taxes', 'categories'));
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
        $taxes = [];
        $tax_total = 0;
        $sub_total = 0;
        $discount_total = 0;
        $discount = $request['discount'];

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

                    $item['name'] = $item_object->name;
                    $item_sku = $item_object->sku;
                }

                $tax = $tax_id = 0;

                if (!empty($item['tax_id'])) {
                    $tax_object = Tax::find($item['tax_id']);

                    $tax_id = $item['tax_id'];

                    $tax = (((double) $item['price'] * (double) $item['quantity']) / 100) * $tax_object->rate;

                    // Apply discount to tax
                    if ($discount) {
                        $tax = $tax - ($tax * ($discount / 100));
                    }
                }

                $bill_item['item_id'] = $item['item_id'];
                $bill_item['name'] = str_limit($item['name'], 180, '');
                $bill_item['sku'] = $item_sku;
                $bill_item['quantity'] = (double) $item['quantity'];
                $bill_item['price'] = (double) $item['price'];
                $bill_item['tax'] = $tax;
                $bill_item['tax_id'] = $tax_id;
                $bill_item['total'] = (double) $item['price'] * (double) $item['quantity'];

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

        $s_total = $sub_total;

        // Apply discount to total
        if ($discount) {
            $s_discount = $s_total * ($discount / 100);
            $discount_total += $s_discount;
            $s_total = $s_total - $s_discount;
        }

        $request['amount'] = $s_total + $tax_total;

        $bill->update($request->input());

        // Upload attachment
        if ($request->file('attachment')) {
            $media = $this->getMedia($request->file('attachment'), 'bills');

            $bill->attachMedia($media, 'attachment');
        }

        // Delete previous bill totals
        BillTotal::where('bill_id', $bill->id)->delete();

        // Add bill totals
        $bill->totals()->delete();
        $this->addTotals($bill, $request, $taxes, $sub_total, $discount_total, $tax_total);

        // Recurring
        $bill->updateRecurring();

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
        $bill->recurring()->delete();
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
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        \Excel::create('bills', function($excel) {
            $bills = Bill::with(['items', 'histories', 'payments', 'totals'])->filter(request()->input())->get();

            $excel->sheet('invoices', function($sheet) use ($bills) {
                $sheet->fromModel($bills->makeHidden([
                    'company_id', 'parent_id', 'created_at', 'updated_at', 'deleted_at', 'attachment', 'discount', 'items', 'histories', 'payments', 'totals', 'media'
                ]));
            });

            $tables = ['items', 'histories', 'payments', 'totals'];
            foreach ($tables as $table) {
                $excel->sheet('bill_' . $table, function($sheet) use ($bills, $table) {
                    $hidden_fields = ['id', 'company_id', 'created_at', 'updated_at', 'deleted_at', 'title'];

                    $i = 1;
                    foreach ($bills as $bill) {
                        $model = $bill->$table->makeHidden($hidden_fields);

                        if ($i == 1) {
                            $sheet->fromModel($model, null, 'A1', false);
                        } else {
                            // Don't put multiple heading columns
                            $sheet->fromModel($model, null, 'A1', false, false);
                        }

                        $i++;
                    }
                });
            }
        })->download('xlsx');
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

        $html = view($bill->template_path, compact('bill'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = 'bill_' . time() . '.pdf';

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
            $message = trans('messages.error.over_payment');

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

        $message = trans('messages.success.added', ['type' => trans_choice('general.payments', 1)]);

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
            $bill->bill_status_code = 'received';
        }

        $bill->save();

        $desc_amount = money((float) $payment->amount, (string) $payment->currency_code, true)->format();

        $description = $desc_amount . ' ' . trans_choice('general.payments', 1);

        // Add bill history
        BillHistory::create([
            'company_id' => $bill->company_id,
            'bill_id' => $bill->id,
            'status_code' => $bill->bill_status_code,
            'notify' => 0,
            'description' => trans('messages.success.deleted', ['type' => $description]),
        ]);

        $payment->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.bills', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    protected function prepareBill(Bill $bill)
    {
        $paid = 0;

        foreach ($bill->payments as $item) {
            $item->default_currency_code = $bill->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $bill->paid = $paid;

        $bill->template_path = 'expenses.bills.bill';

        //event(new BillPrinting($bill));

        return $bill;
    }

    protected function addTotals($bill, $request, $taxes, $sub_total, $discount_total, $tax_total)
    {
        $sort_order = 1;

        // Added bill sub total
        BillTotal::create([
            'company_id' => $request['company_id'],
            'bill_id' => $bill->id,
            'code' => 'sub_total',
            'name' => 'bills.sub_total',
            'amount' => $sub_total,
            'sort_order' => $sort_order,
        ]);

        $sort_order++;

        // Added bill discount
        if ($discount_total) {
            BillTotal::create([
                'company_id' => $request['company_id'],
                'bill_id' => $bill->id,
                'code' => 'discount',
                'name' => 'bills.discount',
                'amount' => $discount_total,
                'sort_order' => $sort_order,
            ]);

            // This is for total
            $sub_total = $sub_total - $discount_total;
        }

        $sort_order++;

        // Added bill taxes
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

        // Added bill total
        BillTotal::create([
            'company_id' => $request['company_id'],
            'bill_id' => $bill->id,
            'code' => 'total',
            'name' => 'bills.total',
            'amount' => $sub_total + $tax_total,
            'sort_order' => $sort_order,
        ]);
    }
}
