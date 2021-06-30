<?php

namespace App\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Transaction;
use App\Models\Setting\Currency;
use App\Http\Requests\Portal\PaymentShow as Request;
use App\Utilities\Modules;
use App\Traits\Transactions;
use Illuminate\Support\Facades\URL;

class Payments extends Controller
{
    use Transactions;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $search = request()->get('search');

        $payments = Transaction::income()->where('contact_id', '=', user()->contact->id)->usingSearchString($search)->sortable('paid_at')->paginate();

        $payment_methods = Modules::getPaymentMethods('all');

        return $this->response('portal.payments.index', compact('payments', 'payment_methods'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Transaction  $payment
     *
     * @return Response
     */
    public function show(Transaction $payment, Request $request)
    {
        $payment_methods = Modules::getPaymentMethods('all');

        return view('portal.payments.show', compact('payment', 'payment_methods'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function currencies()
    {
        $currencies = Currency::collect();

        return $this->response('portal.currencies.index', compact('currencies'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Transaction $payment
     *
     * @return Response
     */
    public function printPayment(Transaction $payment, Request $request)
    {
        event(new \App\Events\Banking\TransactionPrinting($payment));

        $revenue = $payment;
        $view = view($payment->template_path, compact('revenue'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Transaction $payment
     *
     * @return Response
     */
    public function pdfPayment(Transaction $payment, Request $request)
    {
        event(new \App\Events\Banking\TransactionPrinting($payment));

        $currency_style = true;

        $revenue = $payment;
        $view = view($payment->template_path, compact('revenue', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        //$pdf->setPaper('A4', 'portrait');

        $file_name = $this->getTransactionFileName($payment);

        return $pdf->download($file_name);
    }

    public function signed(Transaction $payment)
    {
        if (empty($payment)) {
            return redirect()->route('login');
        }

        $payment_methods = Modules::getPaymentMethods();

        $print_action = URL::signedRoute('signed.payments.print', [$payment->id]);
        $pdf_action = URL::signedRoute('signed.payments.pdf', [$payment->id]);

        return view('portal.payments.signed', compact('payment', 'payment_methods', 'print_action', 'pdf_action'));
    }
}
