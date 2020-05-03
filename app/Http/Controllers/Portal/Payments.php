<?php

namespace App\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Transaction;
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
        $payments = Transaction::income()->where('contact_id', '=', user()->contact->id)->paginate();

        $payment_methods = Modules::getPaymentMethods('all');

        return view('portal.payments.index', compact('payments', 'payment_methods'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Transaction  $payment
     *
     * @return Response
     */
    public function show(Transaction $payment)
    {
        $payment_methods = Modules::getPaymentMethods('all');

        return view('portal.payments.show', compact('payment', 'payment_methods'));
    }
}
