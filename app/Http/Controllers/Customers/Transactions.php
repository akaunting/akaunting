<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Banking\Transaction;

use Auth;

class Transactions extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $transactions = Transaction::getUserTransactions(Auth::user()->customer->id, 'revenues');

        return view('customers.transactions.index', compact('transactions'));
    }
}
