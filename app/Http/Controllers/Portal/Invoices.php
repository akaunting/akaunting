<?php

namespace App\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Models\Sale\Invoice;
use App\Models\Setting\Category;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Sales;
use App\Traits\Uploads;
use App\Utilities\Modules;
use Illuminate\Support\Facades\URL;

class Invoices extends Controller
{
    use DateTime, Currencies, Sales, Uploads;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $invoices = Invoice::with(['contact', 'items', 'payments', 'histories'])
            ->accrued()->where('contact_id', user()->contact->id)
            ->collect(['invoice_number'=> 'desc']);

        $categories = collect(Category::income()->enabled()->orderBy('name')->pluck('name', 'id'));

        $statuses = $this->getInvoiceStatuses();

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
        $payment_methods = Modules::getPaymentMethods();

        event(new \App\Events\Sale\InvoiceViewed($invoice));

        return view('portal.invoices.show', compact('invoice', 'payment_methods'));
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

        foreach ($invoice->transactions as $item) {
            $amount = $item->amount;

            if ($invoice->currency_code != $item->currency_code) {
                $item->default_currency_code = $invoice->currency_code;

                $amount = $item->getAmountConvertedFromDefault();
            }

            $paid += $amount;
        }

        $invoice->paid = $paid;

        $invoice->template_path = 'sales.invoices.print_' . setting('invoice.template' ,'default');

        event(new \App\Events\Sale\InvoicePrinting($invoice));

        return $invoice;
    }

    public function signed(Invoice $invoice)
    {
        if (empty($invoice)) {
            redirect()->route('login');
        }

        $paid = 0;

        foreach ($invoice->transactions as $item) {
            $amount = $item->amount;

            if ($invoice->currency_code != $item->currency_code) {
                $item->default_currency_code = $invoice->currency_code;

                $amount = $item->getAmountConvertedFromDefault();
            }

            $paid += $amount;
        }

        $invoice->paid = $paid;

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

        event(new \App\Events\Sale\InvoiceViewed($invoice));

        return view('portal.invoices.signed', compact('invoice', 'payment_methods', 'payment_actions', 'print_action', 'pdf_action'));
    }
}
