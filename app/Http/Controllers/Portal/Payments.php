<?php

namespace App\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Transaction;
use App\Models\Setting\Currency;
use App\Http\Requests\Portal\PaymentShow as Request;
use App\Utilities\Modules;

class Payments extends Controller
{
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
}
