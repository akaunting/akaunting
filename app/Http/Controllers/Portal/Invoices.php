<?php

namespace App\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Portal\InvoiceShow as Request;
use App\Models\Document\Document;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Documents;
use App\Traits\Uploads;
use App\Utilities\Modules;
use Illuminate\Support\Facades\URL;

class Invoices extends Controller
{
    use DateTime, Currencies, Documents, Uploads;

    /**
     * @var string
     */
    public $type = Document::INVOICE_TYPE;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $invoices = Document::invoice()->with('contact', 'histories', 'items', 'payments')
            ->accrued()->where('contact_id', user()->contact->id)
            ->collect(['document_number'=> 'desc']);

        return $this->response('portal.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function show(Document $invoice, Request $request)
    {
        $payment_methods = Modules::getPaymentMethods();

        event(new \App\Events\Document\DocumentViewed($invoice));

        return view('portal.invoices.show', compact('invoice', 'payment_methods'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function finish(Document $invoice, Request $request)
    {
        $layout = $request->isPortal($invoice->company_id) ? 'portal' : 'signed';

        return view('portal.invoices.finish', compact('invoice', 'layout'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function printInvoice(Document $invoice, Request $request)
    {
        event(new \App\Events\Document\DocumentPrinting($invoice));

        $view = view($invoice->template_path, compact('invoice'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Document $invoice
     *
     * @return Response
     */
    public function pdfInvoice(Document $invoice, Request $request)
    {
        event(new \App\Events\Document\DocumentPrinting($invoice));

        $currency_style = true;

        $view = view($invoice->template_path, compact('invoice', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        //$pdf->setPaper('A4', 'portrait');

        $file_name = $this->getDocumentFileName($invoice);

        return $pdf->download($file_name);
    }

    public function preview(Document $invoice)
    {
        if (empty($invoice)) {
            return redirect()->route('login');
        }

        $payment_actions = [];

        $payment_methods = Modules::getPaymentMethods();

        foreach ($payment_methods as $payment_method_key => $payment_method_value) {
            $codes = explode('.', $payment_method_key);

            if (!isset($payment_actions[$codes[0]])) {
                $payment_actions[$codes[0]] = URL::signedRoute('signed.' . $codes[0] . '.invoices.show', [$invoice->id]);
            }
        }

        return view('portal.invoices.preview', compact('invoice', 'payment_methods', 'payment_actions'));
    }

    public function signed(Document $invoice)
    {
        if (empty($invoice)) {
            return redirect()->route('login');
        }

        $payment_actions = [];

        $payment_methods = Modules::getPaymentMethods();

        foreach ($payment_methods as $payment_method_key => $payment_method_value) {
            $codes = explode('.', $payment_method_key);

            if (!isset($payment_actions[$codes[0]])) {
                $payment_actions[$codes[0]] = URL::signedRoute('signed.' . $codes[0] . '.invoices.show', [$invoice->id]);
            }
        }

        $print_action = URL::signedRoute('signed.invoices.print', [$invoice->id]);
        $pdf_action = URL::signedRoute('signed.invoices.pdf', [$invoice->id]);

        // Guest or Invoice contact user track the invoice viewed.
        if (empty(user()) || user()->id == $invoice->contact->user_id) {
            event(new \App\Events\Document\DocumentViewed($invoice));
        }

        return view('portal.invoices.signed', compact('invoice', 'payment_methods', 'payment_actions', 'print_action', 'pdf_action'));
    }
}
