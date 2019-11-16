<?php

namespace App\Http\Controllers\Incomes;

use App\Abstracts\Http\Controller;
use App\Exports\Incomes\Invoices as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Http\Requests\Income\Invoice as Request;
use App\Http\Requests\Income\InvoiceAddItem as ItemRequest;
use App\Imports\Incomes\Invoices as Import;
use App\Jobs\Income\CreateInvoice;
use App\Jobs\Income\DeleteInvoice;
use App\Jobs\Income\DuplicateInvoice;
use App\Jobs\Income\UpdateInvoice;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceStatus;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Notifications\Income\Invoice as Notification;
use App\Traits\Contacts;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Incomes;
use App\Utilities\Modules;
use File;
use Illuminate\Support\Facades\URL;

class Invoices extends Controller
{
    use Contacts, Currencies, DateTime, Incomes;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $invoices = Invoice::with(['contact', 'items', 'histories', 'status', 'transactions'])->collect(['invoice_number'=> 'desc']);

        $customers = Contact::type($this->getCustomerTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::type('income')->enabled()->orderBy('name')->pluck('name', 'id');

        $statuses = collect(InvoiceStatus::get()->each(function ($item) {
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

        $currency = Currency::where('code', $invoice->currency_code)->first();

        $account_currency_code = Account::where('id', setting('default.account'))->pluck('currency_code')->first();

        $customers = Contact::type($this->getCustomerTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::type('income')->enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $signed_url = URL::signedRoute('signed.invoices.show', [$invoice->id, 'company_id' => session('company_id')]);

        $date_format = $this->getCompanyDateFormat();

        return view('incomes.invoices.show', compact('invoice', 'accounts', 'currencies', 'currency', 'account_currency_code', 'customers', 'categories', 'payment_methods', 'signed_url', 'date_format'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $customers = Contact::type($this->getCustomerTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', setting('default.currency'))->first();

        $items = Item::enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $categories = Category::type('income')->enabled()->orderBy('name')->pluck('name', 'id');

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
        $response = $this->ajaxDispatch(new CreateInvoice($request));

        if ($response['success']) {
            $response['redirect'] = route('invoices.show', $response['data']->id);

            $message = trans('messages.success.added', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('invoices.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
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
        $clone = $this->dispatch(new DuplicateInvoice($invoice));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.invoices', 1)]);

        flash($message)->success();

        return redirect()->route('invoices.edit', $clone->id);
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
            return redirect('common/import/incomes/invoices');
        }

        $message = trans('messages.success.imported', ['type' => trans_choice('general.invoices', 2)]);

        flash($message)->success();

        return redirect('incomes/invoices');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Invoice $invoice
     *
     * @return Response
     */
    public function edit(Invoice $invoice)
    {
        $customers = Contact::type($this->getCustomerTypes())->enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $invoice->currency_code)->first();

        $items = Item::enabled()->orderBy('name')->pluck('name', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $categories = Category::type('income')->enabled()->orderBy('name')->pluck('name', 'id');

        return view('incomes.invoices.edit', compact('invoice', 'customers', 'currencies', 'currency', 'items', 'taxes', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Invoice $invoice
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Invoice $invoice, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateInvoice($invoice, $request));

        if ($response['success']) {
            $response['redirect'] = route('invoices.index');

            $message = trans('messages.success.updated', ['type' => trans_choice('general.invoices', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('invoices.edit', $invoice->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Invoice $invoice
     *
     * @return Response
     */
    public function destroy(Invoice $invoice)
    {
        $response = $this->ajaxDispatch(new DeleteInvoice($invoice));

        $response['redirect'] = route('invoices.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.invoices', 1)]);

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
        return \Excel::download(new Export(), trans_choice('general.invoices', 2) . '.xlsx');
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
        event(new \App\Events\Income\InvoiceSent($invoice));

        $message = trans('invoices.messages.marked_sent');

        flash($message)->success();

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
        if (empty($invoice->contact_email)) {
            return redirect()->back();
        }

        $invoice = $this->prepareInvoice($invoice);

        $view = view($invoice->template_path, compact('invoice'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file = storage_path('app/temp/invoice_'.time().'.pdf');

        $invoice->pdf_path = $file;

        // Save the PDF file into temp folder
        $pdf->save($file);

        // Notify the customer
        $invoice->contact->notify(new Notification($invoice, 'invoice_new_customer'));

        // Delete temp file
        File::delete($file);

        unset($invoice->paid);
        unset($invoice->template_path);
        unset($invoice->pdf_path);
        unset($invoice->reconciled);

        event(new \App\Events\Income\InvoiceSent($invoice));

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
        try {
            event(new \App\Events\Income\PaymentReceived($invoice, []));

            $message = trans('invoices.messages.marked_paid');

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

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $currency = Currency::where('code', $currency_code)->first();

        if (empty($currency)) {
            $currency = Currency::where('code', setting('default.currency', 'USD'))->first();
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

                $amount = $item->getAmountConvertedFromCustomDefault();
            }

            $paid += $amount;
        }

        $invoice->paid = $paid;

        $invoice->template_path = 'incomes.invoices.invoice';

        event(new \App\Events\Income\InvoicePrinting($invoice));

        return $invoice;
    }
}
