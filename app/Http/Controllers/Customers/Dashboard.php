<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Income\Invoice;

class Dashboard extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $customer = auth()->user()->customer;

        $invoices = Invoice::with('status')->accrued()->where('customer_id', $customer->id)->get();

        return view('customers.dashboard.index', compact('customer', 'invoices'));
    }
}
