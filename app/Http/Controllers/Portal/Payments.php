<?php

namespace App\Http\Controllers\portal;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Setting\Category;
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
        $payments = Transaction::type('income')->with(['account', 'category'])->where('contact_id', '=', user()->contact->id)->paginate();

        $payment_methods = Modules::getPaymentMethods('all');

        $categories = collect(Category::type('income')->enabled()->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.categories', 2)]), '');

        $accounts = collect(Account::enabled()->pluck('name', 'id'))
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.accounts', 2)]), '');

        return view('portal.payments.index', compact('payments', 'payment_methods', 'categories', 'accounts'));
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
