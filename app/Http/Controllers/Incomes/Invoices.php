<?php

namespace App\Http\Controllers\Incomes;

use App\Events\InvoiceCreated;
use App\Events\InvoicePrinting;
use App\Events\InvoiceUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Income\Invoice as Request;
use App\Http\Requests\Income\InvoicePayment as PaymentRequest;
use App\Models\Banking\Account;
use App\Models\Income\Customer;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceHistory;
use App\Models\Income\InvoiceItem;
use App\Models\Income\InvoiceTotal;
use App\Models\Income\InvoicePayment;
use App\Models\Income\InvoiceStatus;
use App\Models\Item\Item;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Uploads;
use App\Utilities\Modules;
use Date;

class Invoices extends Controller
{
    use DateTime, Currencies, Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $invoices = Invoice::with(['customer', 'status', 'items', 'payments', 'histories'])->collect();

        $customers = collect(Customer::enabled()->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.customers', 2)]), '');

        $status = collect(InvoiceStatus::all()->pluck('name', 'code'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.statuses', 2)]), '');

        return view('incomes.invoices.index', compact('invoices', 'customers', 'status'));
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
        $paid = 0;

        foreach ($invoice->payments as $item) {
            $item->default_currency_code = $invoice->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $invoice->paid = $paid;

        $accounts = Account::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('general.default_account'))->pluck('currency_code')->first();

        $customers = Customer::enabled()->pluck('name', 'id');

        $categories = Category::enabled()->type('income')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('incomes.invoices.show', compact('invoice', 'accounts', 'currencies', 'account_currency_code', 'customers', 'categories', 'payment_methods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $customers = Customer::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code');

        $items = Item::enabled()->pluck('name', 'id');

        $taxes = Tax::enabled()->pluck('name', 'id');

        // Generate next invoice number
        $prefix = setting('general.invoice_number_prefix', 'INV-');
        $next = setting('general.invoice_number_next', '1');
        $digit = setting('general.invoice_number_digit', '5');
        $number = $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);

        return view('incomes.invoices.create', compact('customers', 'currencies', 'items', 'taxes', 'number'));
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
        // Get customer object
        $customer = Customer::findOrFail($request['customer_id']);

        $request['customer_name'] = $customer->name;
        $request['customer_email'] = $customer->email;
        $request['customer_tax_number'] = $customer->tax_number;
        $request['customer_phone'] = $customer->phone;
        $request['customer_address'] = $customer->address;

        // Get currency object
        $currency = Currency::where('code', $request['currency_code'])->first();

        $request['currency_code'] = $currency->code;
        $request['currency_rate'] = $currency->rate;

        $request['invoice_status_code'] = 'draft';

        $request['amount'] = 0;

        // Upload attachment
        $attachment_path = $this->getUploadedFilePath($request->file('attachment'), 'invoices');

        if ($attachment_path) {
            $request['attachment'] = $attachment_path;
        }

        $invoice = Invoice::create($request->input());

        $taxes = [];
        $tax_total = 0;
        $sub_total = 0;

        $invoice_item = [];
        $invoice_item['company_id'] = $request['company_id'];
        $invoice_item['invoice_id'] = $invoice->id;

        if ($request['item']) {
            foreach ($request['item'] as $item) {
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

                $invoice_item['item_id'] = $item['item_id'];
                $invoice_item['name'] = $item['name'];
                $invoice_item['sku'] = $item_sku;
                $invoice_item['quantity'] = $item['quantity'];
                $invoice_item['price'] = $item['price'];
                $invoice_item['tax'] = $tax;
                $invoice_item['tax_id'] = $tax_id;
                $invoice_item['total'] = $item['price'] * $item['quantity'];

                InvoiceItem::create($invoice_item);

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
                $sub_total += $invoice_item['total'];

                unset($tax_object);
            }
        }

        $request['amount'] = $sub_total + $tax_total;

        $invoice->update($request->input());

        // Add invoice totals
        $this->addTotals($invoice, $request, $taxes, $sub_total, $tax_total);

        $request['invoice_id'] = $invoice->id;
        $request['status_code'] = 'draft';
        $request['notify'] = 0;
        $request['description'] = trans('messages.success.added', ['type' => $request['invoice_number']]);

        InvoiceHistory::create($request->all());

        // Update next invoice number
        $next = setting('general.invoice_number_next', 1) + 1;
        setting(['general.invoice_number_next' => $next]);
        setting()->save();

        // Fire the event to make it extendible
        event(new InvoiceCreated($invoice));

        $message = trans('messages.success.added', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect('incomes/invoices/' . $invoice->id);
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
        $customers = Customer::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code');

        $items = Item::enabled()->pluck('name', 'id');

        $taxes = Tax::enabled()->pluck('name', 'id');

        return view('incomes.invoices.edit', compact('invoice', 'customers', 'currencies', 'items', 'taxes'));
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
        // Get customer object
        $customer = Customer::findOrFail($request['customer_id']);

        $request['customer_name'] = $customer->name;
        $request['customer_email'] = $customer->email;
        $request['customer_tax_number'] = $customer->tax_number;
        $request['customer_phone'] = $customer->phone;
        $request['customer_address'] = $customer->address;

        // Get currency object
        $currency = Currency::where('code', $request['currency_code'])->first();

        $request['currency_code'] = $currency->code;
        $request['currency_rate'] = $currency->rate;

        // Upload attachment
        $attachment_path = $this->getUploadedFilePath($request->file('attachment'), 'invoices');

        if ($attachment_path) {
            $request['attachment'] = $attachment_path;
        }

        $taxes = [];
        $tax_total = 0;
        $sub_total = 0;

        $invoice_item = [];
        $invoice_item['company_id'] = $request['company_id'];
        $invoice_item['invoice_id'] = $invoice->id;

        if ($request['item']) {
            InvoiceItem::where('invoice_id', $invoice->id)->delete();

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

                $invoice_item['item_id'] = $item['item_id'];
                $invoice_item['name'] = $item['name'];
                $invoice_item['sku'] = $item_sku;
                $invoice_item['quantity'] = $item['quantity'];
                $invoice_item['price'] = $item['price'];
                $invoice_item['tax'] = $tax;
                $invoice_item['tax_id'] = $tax_id;
                $invoice_item['total'] = $item['price'] * $item['quantity'];

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
                $sub_total += $invoice_item['total'];

                InvoiceItem::create($invoice_item);
            }
        }

        $request['amount'] = $sub_total + $tax_total;

        $invoice->update($request->input());

        // Delete previous invoice totals
        InvoiceTotal::where('invoice_id', $invoice->id)->delete();

        // Add invoice totals
        $this->addTotals($invoice, $request, $taxes, $sub_total, $tax_total);

        // Fire the event to make it extendible
        event(new InvoiceUpdated($invoice));

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
        $invoice->delete();

        /*
        $invoice->items->delete();
        $invoice->payments->delete();
        $invoice->histories->delete();
        */

        InvoiceItem::where('invoice_id', $invoice->id)->delete();
        InvoiceTotal::where('invoice_id', $invoice->id)->delete();
        InvoicePayment::where('invoice_id', $invoice->id)->delete();
        InvoiceHistory::where('invoice_id', $invoice->id)->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect('incomes/invoices');
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

        flash(trans('invoices.messages.marked_sent'))->success();

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
        $paid = 0;

        foreach ($invoice->payments as $item) {
            $item->default_currency_code = $invoice->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $invoice->paid = $paid;

        $invoice->template_path = 'incomes.invoices.invoice';

        event(new InvoicePrinting($invoice));

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
        $paid = 0;

        foreach ($invoice->payments as $item) {
            $item->default_currency_code = $invoice->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $invoice->paid = $paid;

        $invoice->template_path = 'incomes.invoices.invoice';

        event(new InvoicePrinting($invoice));

        $html = view($invoice->template_path, compact('invoice'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html);

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
            $item->default_currency_code = $invoice->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $amount = $invoice->amount - $paid;

        $request = new PaymentRequest();

        $request['company_id'] = $invoice->company_id;
        $request['invoice_id'] = $invoice->id;
        $request['account_id'] = setting('general.default_account');
        $request['payment_method'] = setting('general.default_payment_method');
        $request['currency_code'] = $invoice->currency_code;
        $request['amount'] = $amount;
        $request['paid_at'] = Date::now();
        $request['_token'] = csrf_token();

        $this->payment($request);

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
        $currency = Currency::where('code', $request['currency_code'])->first();

        $request['currency_code'] = $currency->code;
        $request['currency_rate'] = $currency->rate;

        // Upload attachment
        $attachment_path = $this->getUploadedFilePath($request->file('attachment'), 'invoices');

        if ($attachment_path) {
            $request['attachment'] = $attachment_path;
        }

        $invoice = Invoice::find($request['invoice_id']);

        if ($request['currency_code'] == $invoice->currency_code) {
            if ($request['amount'] > $invoice->amount) {
                $message = trans('messages.error.added', ['type' => trans_choice('general.payment', 1)]);

                return response()->json($message);
            } elseif ($request['amount'] == $invoice->amount) {
                $invoice->invoice_status_code = 'paid';
            } else {
                $invoice->invoice_status_code = 'partial';
            }
        } else {
            $request_invoice = new Invoice();

            $request_invoice->amount = (float) $request['amount'];
            $request_invoice->currency_code = $currency->code;
            $request_invoice->currency_rate = $currency->rate;

            $amount = $request_invoice->getConvertedAmount();

            if ($amount > $invoice->amount) {
                $message = trans('messages.error.added', ['type' => trans_choice('general.payment', 1)]);

                return response()->json($message);
            } elseif ($amount == $invoice->amount) {
                $invoice->invoice_status_code = 'paid';
            } else {
                $invoice->invoice_status_code = 'partial';
            }
        }

        $invoice->save();

        $invoice_payment = InvoicePayment::create($request->input());

        $request['status_code'] = $invoice->invoice_status_code;
        $request['notify'] = 0;

        $desc_date = Date::parse($request['paid_at'])->format($this->getCompanyDateFormat());
        $desc_amount = money((float) $request['amount'], $request['currency_code'], true)->format();
        $request['description'] = $desc_date . ' ' . $desc_amount;

        InvoiceHistory::create($request->input());

        $message = trans('messages.success.added', ['type' => trans_choice('general.revenues', 1)]);

        return response()->json($message);
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
        $payment->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect('incomes/invoices');
    }

    protected function addTotals($invoice, $request, $taxes, $sub_total, $tax_total)
    {
        $sort_order = 1;

        // Added invoice total sub total
        $invoice_sub_total = [
            'company_id' => $request['company_id'],
            'invoice_id' => $invoice->id,
            'code' => 'sub_total',
            'name' => 'invoices.sub_total',
            'amount' => $sub_total,
            'sort_order' => $sort_order,
        ];

        InvoiceTotal::create($invoice_sub_total);

        $sort_order++;

        // Added invoice total taxes
        if ($taxes) {
            foreach ($taxes as $tax) {
                $invoice_tax_total = [
                    'company_id' => $request['company_id'],
                    'invoice_id' => $invoice->id,
                    'code' => 'tax',
                    'name' => $tax['name'],
                    'amount' => $tax['amount'],
                    'sort_order' => $sort_order,
                ];

                InvoiceTotal::create($invoice_tax_total);

                $sort_order++;
            }
        }

        // Added invoice total total
        $invoice_total = [
            'company_id' => $request['company_id'],
            'invoice_id' => $invoice->id,
            'code' => 'total',
            'name' => 'invoices.total',
            'amount' => $sub_total + $tax_total,
            'sort_order' => $sort_order,
        ];

        InvoiceTotal::create($invoice_total);
    }
}
