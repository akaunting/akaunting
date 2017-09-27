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
use App\Models\Income\InvoicePayment;
use App\Models\Income\InvoiceStatus;
use App\Models\Item\Item;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Uploads;

use Jenssegers\Date\Date;

use App\Utilities\Modules;

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
        $invoices = Invoice::with('status')->collect();

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
        $sub_total = 0;
        $tax_total = 0;
        $paid = 0;

        foreach ($invoice->items as $item) {
            $sub_total += ($item->price * $item->quantity);
            $tax_total += ($item->tax * $item->quantity);
        }

        foreach ($invoice->payments as $item) {
            $item->default_currency_code = $invoice->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $invoice->sub_total = $sub_total;
        $invoice->tax_total = $tax_total;
        $invoice->paid = $paid;
        $invoice->grand_total = (($sub_total + $tax_total) - $paid);

        $accounts = Account::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('general.default_account'))->pluck('currency_code')->first();

        $customers = Customer::enabled()->pluck('name', 'id');

        $categories = Category::enabled()->type('income')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('incomes.invoices.show', compact('invoice', 'accounts', 'currencies', 'account_currency_code', 'customers', 'categories', 'payment_methods'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  int  $invoice_id
     *
     * @return Response
     */
    public function printInvoice($invoice_id)
    {
        $sub_total = 0;
        $tax_total = 0;
        $paid = 0;

        $invoice = Invoice::where('id', $invoice_id)->first();

        foreach ($invoice->items as $item) {
            $sub_total += ($item->price * $item->quantity);
            $tax_total += ($item->tax * $item->quantity);
        }

        foreach ($invoice->payments as $item) {
            $item->default_currency_code = $invoice->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $invoice->sub_total = $sub_total;
        $invoice->tax_total = $tax_total;
        $invoice->paid = $paid;
        $invoice->grand_total = (($sub_total + $tax_total) - $paid);

        $invoice->template_path = 'incomes.invoices.invoice';

        event(new InvoicePrinting($invoice));

        return view($invoice->template_path, compact('invoice'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  int  $invoice_id
     *
     * @return Response
     */
    public function pdfInvoice($invoice_id)
    {
        $sub_total = 0;
        $tax_total = 0;
        $paid = 0;

        $invoice = Invoice::where('id', $invoice_id)->first();

        foreach ($invoice->items as $item) {
            $sub_total += ($item->price * $item->quantity);
            $tax_total += ($item->tax * $item->quantity);
        }

        foreach ($invoice->payments as $item) {
            $item->default_currency_code = $invoice->currency_code;

            $paid += $item->getDynamicConvertedAmount();
        }

        $invoice->sub_total = $sub_total;
        $invoice->tax_total = $tax_total;
        $invoice->paid = $paid;
        $invoice->grand_total = (($sub_total + $tax_total) - $paid);

        $invoice->template_path = 'incomes.invoices.invoice';

        event(new InvoicePrinting($invoice));

        $html = view($invoice->template_path, compact('invoice'))->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = 'invoice_'.time().'.pdf';

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

        return view('incomes.invoices.create', compact('customers', 'currencies', 'items', 'taxes'));
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

        $invoice_item = array();
        $invoice_item['company_id'] = $request['company_id'];
        $invoice_item['invoice_id'] = $invoice->id;

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

                $invoice_item['item_id'] = $item['item_id'];
                $invoice_item['name'] = $item['name'];
                $invoice_item['sku'] = $item_sku;
                $invoice_item['quantity'] = $item['quantity'];
                $invoice_item['price'] = $item['price'];
                $invoice_item['tax'] = (($item['price'] * $item['quantity']) / 100) * $tax_rate;
                $invoice_item['tax_id'] = $tax_id;
                $invoice_item['total'] = ($item['price'] + $invoice_item['tax']) * $item['quantity'];

                $request['amount'] += $invoice_item['total'];

                InvoiceItem::create($invoice_item);
            }
        }

        $invoice->update($request->input());

        $request['invoice_id'] = $invoice->id;
        $request['status_code'] = 'draft';
        $request['notify'] = 0;
        $request['description'] = trans('messages.success.added', ['type' => $request['invoice_number']]);

        InvoiceHistory::create($request->all());

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

        $request['invoice_status_code'] = 'draft';

        $request['amount'] = 0;

        // Upload attachment
        $attachment_path = $this->getUploadedFilePath($request->file('attachment'), 'invoices');
        if ($attachment_path) {
            $request['attachment'] = $attachment_path;
        }

        $invoice_item = array();
        $invoice_item['company_id'] = $request['company_id'];
        $invoice_item['invoice_id'] = $invoice->id;

        if ($request['item']) {
            InvoiceItem::where('invoice_id', $invoice->id)->delete();

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

                $invoice_item['item_id'] = $item['item_id'];
                $invoice_item['name'] = $item['name'];
                $invoice_item['sku'] = $item_sku;
                $invoice_item['quantity'] = $item['quantity'];
                $invoice_item['price'] = $item['price'];
                $invoice_item['tax'] = (($item['price'] * $item['quantity']) / 100 * $tax_rate);
                $invoice_item['tax_id'] = $tax_id;
                $invoice_item['total'] = ($item['price'] + $invoice_item['tax']) * $item['quantity'];

                $request['amount'] += $invoice_item['total'];

                InvoiceItem::create($invoice_item);
            }
        }

        $invoice->update($request->input());

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
        InvoicePayment::where('invoice_id', $invoice->id)->delete();
        InvoiceHistory::where('invoice_id', $invoice->id)->delete();

        $message = trans('messages.success.deleted', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect('incomes/invoices');
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
}
