<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Contact as Request;
use App\Models\Common\Contact;
use App\Models\Setting\Currency;

class Vendors extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-expenses-vendors')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-expenses-vendors')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-expenses-vendors')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-expenses-vendors')->only('destroy');
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

        $html = view('modals.vendors.create', compact('currencies', 'contact_selector', 'rand'))->render();

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

        $contact = Contact::create($request->all());

        $message = trans('messages.success.added', ['type' => trans_choice('general.vendors', 1)]);

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $contact,
            'message' => $message,
            'html' => 'null',
        ]);
    }
}
