<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;

use Auth;

class Dashboard extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user()->customer;

        return view('customers.dashboard.index', compact('user'));
    }
}
