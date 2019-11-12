<?php

namespace App\Http\Controllers\Expenses;

use App\Events\BillCreated;
//use App\Events\BillPrinting;
use App\Events\BillUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\Bill as Request;
use App\Http\Requests\Expense\BillAddItem as ItemRequest;
use App\Http\Requests\Expense\BillPayment as PaymentRequest;
use App\Jobs\Expense\CreateBill;
use App\Jobs\Expense\UpdateBill;
use App\Jobs\Expense\CreateBillPayment;
use App\Models\Banking\Account;
use App\Models\Common\Media;
use App\Models\Expense\BillStatus;
use App\Models\Expense\Vendor;
use App\Models\Expense\Bill;
use App\Models\Expense\BillItem;
use App\Models\Expense\BillItemTax;
use App\Models\Expense\BillTotal;
use App\Models\Expense\BillHistory;
use App\Models\Expense\BillPayment;
use App\Models\Common\Item;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
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

        $vendors = collect(Vendor::enabled()->orderBy('name')->pluck('name', 'id'));

        $categories = collect(Category::enabled()->type('expense')->orderBy('name')->pluck('name', 'id'));

        $statuses = collect(BillStatus::get()->each(function($item) {
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

        $currency = Currency::where('code', '=', setting('general.default_currency'))->first();

        $items = Item::enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $categories = Category::enabled()->type('expense')->orderBy('name')->pluck('name', 'id');

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
        $bill = dispatch(new CreateBill($request));

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

        $allowed_sheets = ['bills', 'bill_items', 'bill_item_taxes', 'bill_histories', 'bill_payments', 'bill_totals'];

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

        $currency = Currency::where('code', '=', $bill->currency_code)->first();

        $items = Item::enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('rate')->get()->pluck('title', 'id');

        $categories = Category::enabled()->type('expense')->orderBy('name')->pluck('name', 'id');

        return view('expenses.bills.edit', compact('bill', 'vendors', 'currencies', 'currency', 'items', 'taxes', 'categories'));
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
        $bill = dispatch(new UpdateBill($bill, $request));

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
        // Decrease stock
        $bill->items()->each(function ($bill_item) {
            $item = Item::find($bill_item->item_id);

            if (empty($item)) {
                return;
            }

            $item->quantity += (double) $bill_item->quantity;
            $item->save();
        });

        $this->deleteRelationships($bill, ['items', 'item_taxes', 'histories', 'payments', 'recurring', 'totals']);
        $bill->delete();

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
        \Excel::create('bills', function ($excel) {
            $bills = Bill::with(['items', 'item_taxes', 'histories', 'payments', 'totals'])->filter(request()->input())->get();

            $excel->sheet('bills', function ($sheet) use ($bills) {
                $sheet->fromModel($bills->makeHidden([
                    'company_id', 'parent_id', 'created_at', 'updated_at', 'deleted_at', 'attachment', 'discount', 'items', 'item_taxes', 'histories', 'payments', 'totals', 'media', 'paid', 'amount_without_tax'
                ]));
            });

            $tables = ['items', 'item_taxes', 'histories', 'payments', 'totals'];
            foreach ($tables as $table) {
                $excel->sheet('bill_' . $table, function ($sheet) use ($bills, $table) {
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

        // Add bill history
        BillHistory::create([
            'company_id' => $bill->company_id,
            'bill_id' => $bill->id,
            'status_code' => 'received',
            'notify' => 0,
            'description' => trans('bills.mark_recevied'),
        ]);

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

        $currency_style = true;

        $view = view($bill->template_path, compact('bill', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES');

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
        $currencies = Currency::enabled()->pluck('rate', 'code')->toArray();
        $currency = Currency::where('code', $request['currency_code'])->first();

        $request['currency_code'] = $currency->code;
        $request['currency_rate'] = $currency->rate;

        $bill = Bill::find($request['bill_id']);

        $total_amount = $bill->amount;

        $default_amount = (double) $request['amount'];

        if ($bill->currency_code == $request['currency_code']) {
            $amount = $default_amount;
        } else {
            $default_amount_model = new BillPayment();

            $default_amount_model->default_currency_code = $bill->currency_code;
            $default_amount_model->amount                = $default_amount;
            $default_amount_model->currency_code         = $request['currency_code'];
            $default_amount_model->currency_rate         = $currencies[$request['currency_code']];

            $default_amount = (double) $default_amount_model->getDivideConvertedAmount();

            $convert_amount = new BillPayment();

            $convert_amount->default_currency_code = $request['currency_code'];
            $convert_amount->amount = $default_amount;
            $convert_amount->currency_code = $bill->currency_code;
            $convert_amount->currency_rate = $currencies[$bill->currency_code];

            $amount = (double) $convert_amount->getDynamicConvertedAmount();
        }

        if ($bill->payments()->count()) {
            $total_amount -= $bill->payments()->paid();
        }

        // For amount cover integer
        $multiplier = 1;

        for ($i = 0; $i < $currency->precision; $i++) {
            $multiplier *= 10;
        }

        $amount_check = (int) ($amount * $multiplier);
        $total_amount_check = (int) (round($total_amount, $currency->precision) * $multiplier);

        if ($amount_check > $total_amount_check) {
            $error_amount = $total_amount;

            if ($bill->currency_code != $request['currency_code']) {
                $error_amount_model = new BillPayment();

                $error_amount_model->default_currency_code = $request['currency_code'];
                $error_amount_model->amount                = $error_amount;
                $error_amount_model->currency_code         = $bill->currency_code;
                $error_amount_model->currency_rate         = $currencies[$bill->currency_code];

                $error_amount = (double) $error_amount_model->getDivideConvertedAmount();

                $convert_amount = new BillPayment();

                $convert_amount->default_currency_code = $bill->currency_code;
                $convert_amount->amount = $error_amount;
                $convert_amount->currency_code = $request['currency_code'];
                $convert_amount->currency_rate = $currencies[$request['currency_code']];

                $error_amount = (double) $convert_amount->getDynamicConvertedAmount();
            }

            $message = trans('messages.error.over_payment', ['amount' => money($error_amount, $request['currency_code'], true)]);

            return response()->json([
                'success' => false,
                'error' => true,
                'data' => [
                    'amount' => $error_amount
                ],
                'message' => $message,
                'html' => 'null',
            ]);
        } elseif ($amount_check == $total_amount_check) {
            $bill->bill_status_code = 'paid';
        } else {
            $bill->bill_status_code = 'partial';
        }

        $bill->save();

        $bill_payment = dispatch(new CreateBillPayment($request, $bill));

        // Upload attachment
        if ($request->file('attachment')) {
            $media = $this->getMedia($request->file('attachment'), 'bills');

            $bill_payment->attachMedia($media, 'attachment');
        }

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

    public function addItem(ItemRequest $request)
    {
        $item_row = $request['item_row'];
        $currency_code = $request['currency_code'];

        $taxes = Tax::enabled()->orderBy('rate')->get()->pluck('title', 'id');

        $currency = Currency::where('code', '=', $currency_code)->first();

        if (empty($currency)) {
            $currency = Currency::where('code', '=', setting('general.default_currency'))->first();
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

                $amount = $item->getDynamicConvertedAmount();
            }

            $paid += $amount;
        }

        $bill->paid = $paid;

        $bill->template_path = 'expenses.bills.bill';

        //event(new BillPrinting($bill));

        return $bill;
    }
}
