<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Models\Common\Contact;

class Contacts extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:read-sales-customers|read-purchases-vendors')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success'   => true,
            'error'     => false,
            'data'      => Contact::collect(),
            'message'   => '',
        ]);
    }
}
