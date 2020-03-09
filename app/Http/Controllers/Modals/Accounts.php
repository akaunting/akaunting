<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Banking\Account as Request;
use App\Jobs\Banking\CreateAccount;
use App\Models\Banking\Account;
use App\Models\Setting\Currency;

class Accounts extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-banking-accounts')->only(['create', 'store', 'duplicate', 'import']);
        $this->middleware('permission:read-banking-accounts')->only(['index', 'show', 'edit', 'export']);
        $this->middleware('permission:update-banking-accounts')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-banking-accounts')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $currencies = Currency::enabled()->pluck('name', 'code');

        $currency = Currency::where('code', '=', setting('default.currency'))->first();

        $html = view('modals.accounts.create', compact('currencies', 'currency'))->render();

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

        $response = $this->ajaxDispatch(new CreateAccount($request));

        if ($response['success']) {
            $response['message'] = trans('messages.success.added', ['type' => trans_choice('general.accounts', 1)]);
        }

        return response()->json($response);
    }
}
