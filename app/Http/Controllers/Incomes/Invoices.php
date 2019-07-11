<?php

namespace App\Http\Controllers\Incomes;

use App\Events\InvoiceCreated;
use App\Events\InvoicePrinting;
use App\Events\InvoiceUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Income\Invoice as Request;
use App\Http\Requests\Income\InvoiceAddItem as ItemRequest;
use App\Http\Requests\Income\InvoicePayment as PaymentRequest;
use App\Jobs\Income\CreateInvoice;
use App\Jobs\Income\UpdateInvoice;
use App\Jobs\Income\CreateInvoicePayment;
use App\Models\Banking\Account;
use App\Models\Common\Item;
use App\Models\Common\Media;
use App\Models\Income\Customer;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceHistory;
use App\Models\Income\InvoiceItem;
use App\Models\Income\InvoiceItemTax;
use App\Models\Income\InvoiceTotal;
use App\Models\Income\InvoicePayment;
use App\Models\Income\InvoiceStatus;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Notifications\Income\Invoice as Notification;
use App\Notifications\Common\Item as ItemNotification;
use App\Notifications\Common\ItemReminder as ItemReminderNotification;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Incomes;
use App\Traits\Uploads;
use App\Utilities\Import;
use App\Utilities\ImportFile;
use App\Utilities\Modules;
use Date;
use File;
use Image;
use Storage;
use SignedUrl;

class Invoices extends Controller
{
    use DateTime, Currencies, Incomes, Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $invoices = Invoice::with(['customer', 'status', 'items', 'payments', 'histories'])->collect(['invoice_number'=> 'desc']);

        $customers = collect(Customer::enabled()->orderBy('name')->pluck('name', 'id'));

        $categories = collect(Category::enabled()->type('income')->orderBy('name')->pluck('name', 'id'));

        $statuses = collect(InvoiceStatus::get()->each(function($item) {
            $item->name = trans('invoices.status.' . $item->code);
            return $item;
        })->pluck('name', 'code'));

        return view('incomes.invoices.index', compact('invoices', 'customers', 'categories', 'statuses'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Invoice  $invoice
     *
     * @return Response
     */
    public function show(Invoice $invoice)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('general.default_account'))->pluck('currency_code')->first();

        $customers = Customer::enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::enabled()->type('income')->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $customer_share = SignedUrl::sign(route('signed.invoices', $invoice->id));

        return view('incomes.invoices.show', compact('invoice', 'accounts', 'currencies', 'account_currency_code', 'customers', 'categories', 'payment_methods', 'customer_share'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $customers = Customer::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code');

        $currency = Currency::where('code', '=', setting('general.default_currency'))->first();

        $items = Item::enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $categories = Category::enabled()->type('income')->orderBy('name')->pluck('name', 'id');

        $number = $this->getNextInvoiceNumber();

        return view('incomes.invoices.create', compact('customers', 'currencies', 'currency', 'items', 'taxes', 'categories', 'number'));
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
        $invoice = dispatch(new CreateInvoice($request));

        $message = trans('messages.success.added', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect('incomes/invoices/' . $invoice->id);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Invoice  $invoice
     *
     * @return Response
     */
    public function duplicate(Invoice $invoice)
    {
        $clone = $invoice->duplicate();

        // Add invoice history
        InvoiceHistory::create([
            'company_id' => session('company_id'),
            'invoice_id' => $clone->id,
            'status_code' => 'draft',
            'notify' => 0,
            'description' => trans('messages.success.added', ['type' => $clone->invoice_number]),
        ]);

        // Update next invoice number
        $this->increaseNextInvoiceNumber();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect('incomes/invoices/' . $clone->id . '/edit');
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

        $allowed_sheets = ['invoices', 'invoice_items', 'invoice_item_taxes', 'invoice_histories', 'invoice_payments', 'invoice_totals'];

        // Loop through all sheets
        $import->each(function ($sheet) use (&$success, $allowed_sheets) {
            $sheet_title = $sheet->getTitle();

            if (!in_array($sheet_title, $allowed_sheets)) {
                $message = trans('messages.error.import_sheet');

                flash($message)->error()->important();

                return false;
            }

            $slug = 'Income\\' . str_singular(studly_case($sheet_title));

            if (!$success = Import::createFromSheet($sheet, $slug)) {
                return false;
            }
        });

        if (!$success) {
            return redirect('common/import/incomes/invoices');
        }

        $message = trans('messages.success.imported', ['type' => trans_choice('general.invoices', 2)]);

        flash($message)->success();

        return redirect('incomes/invoices');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Invoice  $invoice
     *
     * @return Response
     */
    public function edit(Invoice $invoice)
    {
        $customers = Customer::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code');

        $currency = Currency::where('code', '=', $invoice->currency_code)->first();

        $items = Item::enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $categories = Category::enabled()->type('income')->orderBy('name')->pluck('name', 'id');

        return view('incomes.invoices.edit', compact('invoice', 'customers', 'currencies', 'currency', 'items', 'taxes', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Invoice  $invoice
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Invoice $invoice, Request $request)
    {
        $invoice = dispatch(new UpdateInvoice($invoice, $request));

        $message = trans('messages.success.updated', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect('incomes/invoices/' . $invoice->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Invoice  $invoice
     *
     * @return Response
     */
    public function destroy(Invoice $invoice)
    {
        // Increase stock
        $invoice->items()->each(function ($invoice_item) {
            $item = Item::find($invoice_item->item_id);

            if (empty($item)) {
                return;
            }

            $item->quantity += (double) $invoice_item->quantity;
            $item->save();
        });

        $this->deleteRelationships($invoice, ['items', 'item_taxes', 'histories', 'payments', 'recurring', 'totals']);
        $invoice->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect('incomes/invoices');
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        \Excel::create('invoices', function ($excel) {
            $invoices = Invoice::with(['items', 'item_taxes', 'histories', 'payments', 'totals'])->filter(request()->input())->get();

            $excel->sheet('invoices', function ($sheet) use ($invoices) {
                $sheet->fromModel($invoices->makeHidden([
                    'company_id', 'parent_id', 'created_at', 'updated_at', 'deleted_at', 'attachment', 'discount', 'items', 'item_taxes', 'histories', 'payments', 'totals', 'media', 'paid', 'amount_without_tax'
                ]));
            });

            $tables = ['items', 'item_taxes', 'histories', 'payments', 'totals'];
            foreach ($tables as $table) {
                $excel->sheet('invoice_' . $table, function ($sheet) use ($invoices, $table) {
                    $hidden_fields = ['id', 'company_id', 'created_at', 'updated_at', 'deleted_at', 'title'];

                    $i = 1;

                    foreach ($invoices as $invoice) {
                        $model = $invoice->$table->makeHidden($hidden_fields);

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
     * Mark the invoice as sent.
     *
     * @param  Invoice $invoice
     *
     * @return Response
     */
    public function markSent(Invoice $invoice)
    {
        $invoice->invoice_status_code = 'sent';

        $invoice->save();

        // Add invoice history
        InvoiceHistory::create([
            'company_id' => $invoice->company_id,
            'invoice_id' => $invoice->id,
            'status_code' => 'sent',
            'notify' => 0,
            'description' => trans('invoices.mark_sent'),
        ]);

        flash(trans('invoices.messages.marked_sent'))->success();

        return redirect()->back();
    }

    /**
     * Download the PDF file of invoice.
     *
     * @param  Invoice $invoice
     *
     * @return Response
     */
    public function emailInvoice(Invoice $invoice)
    {
        if (empty($invoice->customer_email)) {
            return redirect()->back();
        }

        $invoice = $this->prepareInvoice($invoice);

        $view = view($invoice->template_path, compact('invoice'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES');

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file = storage_path('app/temp/invoice_'.time().'.pdf');

        $invoice->pdf_path = $file;

        // Save the PDF file into temp folder
        $pdf->save($file);

        // Notify the customer
        $invoice->customer->notify(new Notification($invoice));

        // Delete temp file
        File::delete($file);

        unset($invoice->paid);
        unset($invoice->template_path);
        unset($invoice->pdf_path);
        unset($invoice->reconciled);

        // Mark invoice as sent
        if ($invoice->invoice_status_code != 'partial') {
            $invoice->invoice_status_code = 'sent';

            $invoice->save();
        }

        // Add invoice history
        InvoiceHistory::create([
            'company_id' => $invoice->company_id,
            'invoice_id' => $invoice->id,
            'status_code' => 'sent',
            'notify' => 1,
            'description' => trans('invoices.send_mail'),
        ]);

        flash(trans('invoices.messages.email_sent'))->success();

        return redirect()->back();
    }

    /**
     * Print the invoice.
     *
     * @param  Invoice $invoice
     *
     * @return Response
     */
    public function printInvoice(Invoice $invoice)
    {
        $invoice = $this->prepareInvoice($invoice);

        return view($invoice->template_path, compact('invoice'));
    }

    /**
     * Download the PDF file of invoice.
     *
     * @param  Invoice $invoice
     *
     * @return Response
     */
    public function pdfInvoice(Invoice $invoice)
    {
        $invoice = $this->prepareInvoice($invoice);

        $currency_style = true;

        $view = view($invoice->template_path, compact('invoice', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        //$pdf->setPaper('A4', 'portrait');

        $file_name = 'invoice_'.time().'.pdf';

        return $pdf->download($file_name);
    }

    /**
     * Mark the invoice as paid.
     *
     * @param  Invoice $invoice
     *
     * @return Response
     */
    public function markPaid(Invoice $invoice)
    {
        $paid = 0;

        foreach ($invoice->payments as $item) {
            $amount = $item->amount;

            if ($invoice->currency_code != $item->currency_code) {
                $item->default_currency_code = $invoice->currency_code;

                $amount = $item->getDynamicConvertedAmount();
            }

            $paid += $amount;
        }

        $amount = $invoice->amount - $paid;

        if (!empty($amount)) {
            $request = new PaymentRequest();

            $request['company_id'] = $invoice->company_id;
            $request['invoice_id'] = $invoice->id;
            $request['account_id'] = setting('general.default_account');
            $request['payment_method'] = setting('general.default_payment_method', 'offlinepayment.cash.1');
            $request['currency_code'] = $invoice->currency_code;
            $request['amount'] = $amount;
            $request['paid_at'] = Date::now()->format('Y-m-d');
            $request['_token'] = csrf_token();

            $this->payment($request);
        } else {
            $invoice->invoice_status_code = 'paid';
            $invoice->save();
        }

        return redirect()->back();
    }

    /**
     * Add payment to the invoice.
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

        $invoice = Invoice::find($request['invoice_id']);

        $total_amount = $invoice->amount;

        $default_amount = (double) $request['amount'];

        if ($invoice->currency_code == $request['currency_code']) {
            $amount = $default_amount;
        } else {
            $default_amount_model = new InvoicePayment();

            $default_amount_model->default_currency_code = $invoice->currency_code;
            $default_amount_model->amount                = $default_amount;
            $default_amount_model->currency_code         = $request['currency_code'];
            $default_amount_model->currency_rate         = $currencies[$request['currency_code']];

            $default_amount = (double) $default_amount_model->getDivideConvertedAmount();

            $convert_amount = new InvoicePayment();

            $convert_amount->default_currency_code = $request['currency_code'];
            $convert_amount->amount = $default_amount;
            $convert_amount->currency_code = $invoice->currency_code;
            $convert_amount->currency_rate = $currencies[$invoice->currency_code];

            $amount = (double) $convert_amount->getDynamicConvertedAmount();
        }

        if ($invoice->payments()->count()) {
            $total_amount -= $invoice->payments()->paid();
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

            if ($invoice->currency_code != $request['currency_code']) {
                $error_amount_model = new InvoicePayment();

                $error_amount_model->default_currency_code = $request['currency_code'];
                $error_amount_model->amount                = $error_amount;
                $error_amount_model->currency_code         = $invoice->currency_code;
                $error_amount_model->currency_rate         = $currencies[$invoice->currency_code];

                $error_amount = (double) $error_amount_model->getDivideConvertedAmount();

                $convert_amount = new InvoicePayment();

                $convert_amount->default_currency_code = $invoice->currency_code;
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
            $invoice->invoice_status_code = 'paid';
        } else {
            $invoice->invoice_status_code = 'partial';
        }

        $invoice->save();

        $invoice_payment = dispatch(new CreateInvoicePayment($request, $invoice));

        // Upload attachment
        if ($request->file('attachment')) {
            $media = $this->getMedia($request->file('attachment'), 'invoices');

            $invoice_payment->attachMedia($media, 'attachment');
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
     * @param  InvoicePayment  $payment
     *
     * @return Response
     */
    public function paymentDestroy(InvoicePayment $payment)
    {
        $invoice = Invoice::find($payment->invoice_id);

        if ($invoice->payments()->count() > 1) {
            $invoice->invoice_status_code = 'partial';
        } else {
            $invoice->invoice_status_code = 'sent';
        }

        $invoice->save();

        $desc_amount = money((float) $payment->amount, (string) $payment->currency_code, true)->format();

        $description = $desc_amount . ' ' . trans_choice('general.payments', 1);

        // Add invoice history
        InvoiceHistory::create([
            'company_id' => $invoice->company_id,
            'invoice_id' => $invoice->id,
            'status_code' => $invoice->invoice_status_code,
            'notify' => 0,
            'description' => trans('messages.success.deleted', ['type' => $description]),
        ]);

        $payment->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.invoices', 1)]);

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

        $html = view('incomes.invoices.item', compact('item_row', 'taxes', 'currency'))->render();

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

    protected function prepareInvoice(Invoice $invoice)
    {
        $paid = 0;

        foreach ($invoice->payments as $item) {
            $amount = $item->amount;

            if ($invoice->currency_code != $item->currency_code) {
                $item->default_currency_code = $invoice->currency_code;

                $amount = $item->getDynamicConvertedAmount();
            }

            $paid += $amount;
        }

        $invoice->paid = $paid;

        $invoice->template_path = 'incomes.invoices.invoice';

        event(new InvoicePrinting($invoice));

        return $invoice;
    }
}
