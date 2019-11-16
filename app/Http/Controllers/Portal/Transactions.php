<?php

namespace App\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
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
        $transactions = Transaction::type('income')->where('contact_id', user()->contact->id)->get();

        return view('portal.transactions.index', compact('transactions'));
    }
}
