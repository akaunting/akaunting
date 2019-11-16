<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Contact as Request;
use App\Models\Common\Contact;
use App\Models\Setting\Currency;

class Customers extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-incomes-customers')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-incomes-customers')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-incomes-customers')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-incomes-customers')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $contact_selector = false;

        if (request()->has('contact_selector')) {
            $contact_selector = request()->get('contact_selector');
        }

        $rand = rand();

        $html = view('modals.customers.create', compact('currencies', 'contact_selector', 'rand'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request['enabled'] = 1;

        $customer = Contact::create($request->all());

        $message = trans('messages.success.added', ['type' => trans_choice('general.customers', 1)]);

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $customer,
            'message' => $message,
            'html' => 'null',
        ]);
    }
}
