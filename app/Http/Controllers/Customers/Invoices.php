<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Events\InvoicePrinting;
use App\Models\Banking\Account;
use App\Models\Income\Customer;
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
use Image;
use Storage;

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
            $item->default_currency_code = $invoice->currency_code;

            $paid += $item->getDynamicConvertedAmount();
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
}
