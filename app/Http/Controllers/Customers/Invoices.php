<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Events\InvoicePrinting;
use App\Models\Banking\Account;
use App\Models\Income\Customer;
use App\Models\Income\Revenue;
use App\Models\Income\Invoice;
use App\Models\Income\InvoiceStatus;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Common\Media;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Uploads;
use App\Utilities\Modules;
use File;
use Illuminate\Http\Request;
use Image;
use Storage;
use SignedUrl;

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
        $invoices = Invoice::with('status')->accrued()->where('customer_id', auth()->user()->customer->id)->paginate();

        $status = collect(InvoiceStatus::all()->pluck('name', 'code'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.statuses', 2)]), '');

        return view('customers.invoices.index', compact('invoices', 'status'));
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
            $amount = $item->amount;

            if ($invoice->currency_code != $item->currency_code) {
                $item->default_currency_code = $invoice->currency_code;

                $amount = $item->getDynamicConvertedAmount();
            }

            $paid += $amount;
        }

        $invoice->paid = $paid;

        $accounts = Account::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('general.default_account'))->pluck('currency_code')->first();

        $customers = Customer::enabled()->pluck('name', 'id');

        $categories = Category::enabled()->type('income')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('customers.invoices.show', compact('invoice', 'accounts', 'currencies', 'account_currency_code', 'customers', 'categories', 'payment_methods'));
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

        $logo = $this->getLogo();

        return view($invoice->template_path, compact('invoice', 'logo'));
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

        $logo = $this->getLogo();

        $html = view($invoice->template_path, compact('invoice', 'logo'))->render();

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

                $amount = $item->getDynamicConvertedAmount();
            }

            $paid += $amount;
        }

        $invoice->paid = $paid;

        $invoice->template_path = 'incomes.invoices.invoice';

        event(new InvoicePrinting($invoice));

        return $invoice;
    }

    protected function getLogo()
    {
        $logo = '';

        $media_id = setting('general.company_logo');

        if (setting('general.invoice_logo')) {
            $media_id = setting('general.invoice_logo');
        }

        $media = Media::find($media_id);

        if (!empty($media)) {
            $path = Storage::path($media->getDiskPath());

            if (!is_file($path)) {
                return $logo;
            }
        } else {
            $path = asset('public/img/company.png');
        }

        $image = Image::make($path)->encode()->getEncoded();

        if (empty($image)) {
            return $logo;
        }

        $extension = File::extension($path);

        $logo = 'data:image/' . $extension . ';base64,' . base64_encode($image);

        return $logo;
    }

    public function link(Invoice $invoice, Request $request)
    {
        session(['company_id' => $invoice->company_id]);

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

        $accounts = Account::enabled()->pluck('name', 'id');

        $currencies = Currency::enabled()->pluck('name', 'code')->toArray();

        $account_currency_code = Account::where('id', setting('general.default_account'))->pluck('currency_code')->first();

        $customers = Customer::enabled()->pluck('name', 'id');

        $categories = Category::enabled()->type('income')->pluck('name', 'id');

        $payment_methods = Modules::getPaymentMethods();

        $payment_actions = [];

        foreach ($payment_methods as $payment_method_key => $payment_method_value) {
            $codes = explode('.', $payment_method_key);

            if (!isset($payment_actions[$codes[0]])) {
                $payment_actions[$codes[0]] = SignedUrl::sign(url('links/invoices/' . $invoice->id . '/' . $codes[0]), 1);
            }
        }

        $print_action = SignedUrl::sign(url('links/invoices/' . $invoice->id . '/print'), 1);
        $pdf_action = SignedUrl::sign(url('links/invoices/' . $invoice->id . '/pdf'), 1);

        return view('customers.invoices.link', compact('invoice', 'accounts', 'currencies', 'account_currency_code', 'customers', 'categories', 'payment_methods', 'payment_actions', 'print_action', 'pdf_action'));
    }
}
