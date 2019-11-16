<?php

namespace App\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceStatus;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Uploads;
use App\Utilities\Modules;
use Illuminate\Support\Facades\URL;

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
        $invoices = Invoice::with(['contact', 'status', 'items', 'payments', 'histories'])
            ->accrued()->where('contact_id', user()->contact->id)
            ->collect(['invoice_number'=> 'desc']);

        $categories = collect(Category::type('income')->enabled()->orderBy('name')->pluck('name', 'id'));

        $statuses = collect(InvoiceStatus::get()->each(function ($item) {
            $item->name = trans('invoices.status.' . $item->code);
            return $item;
        })->pluck('name', 'code'));

        return view('portal.invoices.index', compact('invoices', 'categories', 'statuses'));
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

        $account_currency_code = Account::where('id', setting('default.account'))->pluck('currency_code')->first();

        $customers = Contact::type('customer')->enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::type('income')->enabled()->orderBy('name')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        event(new \App\Events\Income\InvoiceViewed($invoice));

        return view('portal.invoices.show', compact('invoice', 'accounts', 'currencies', 'account_currency_code', 'customers', 'categories', 'payment_methods'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Invoice  $invoice
     *
     * @return Response
     */
    public function printInvoice(Invoice $invoice)
    {
        $invoice = $this->prepareInvoice($invoice);

        return view($invoice->template_path, compact('invoice'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Invoice  $invoice
     *
     * @return Response
     */
    public function pdfInvoice(Invoice $invoice)
    {
        $invoice = $this->prepareInvoice($invoice);

        $currency_style = true;

        $view = view($invoice->template_path, compact('invoice', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES');

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html);

        //$pdf->setPaper('A4', 'portrait');

        $file_name = 'invoice_' . time() . '.pdf';

        return $pdf->download($file_name);
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

    public function signed(Invoice $invoice)
    {
        if (empty($invoice)) {
            redirect()->route('login');
        }

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

        $accounts = Account::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('default.account'))->pluck('currency_code')->first();

        $customers = Contact::type('customer')->enabled()->pluck('name', 'id');

        $categories = Category::type('income')->enabled()->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $payment_actions = [];

        foreach ($payment_methods as $payment_method_key => $payment_method_value) {
            $codes = explode('.', $payment_method_key);

            if (!isset($payment_actions[$codes[0]])) {
                $payment_actions[$codes[0]] = URL::signedRoute('signed.invoices.' . $codes[0] . '.show', [$invoice->id, 'company_id' => session('company_id')]);
            }
        }

        $print_action = URL::signedRoute('signed.invoices.print', [$invoice->id, 'company_id' => session('company_id')]);
        $pdf_action = URL::signedRoute('signed.invoices.pdf', [$invoice->id, 'company_id' => session('company_id')]);

        event(new \App\Events\Income\InvoiceViewed($invoice));

        return view('portal.invoices.signed', compact('invoice', 'accounts', 'currencies', 'account_currency_code', 'customers', 'categories', 'payment_methods', 'payment_actions', 'print_action', 'pdf_action'));
    }
}
