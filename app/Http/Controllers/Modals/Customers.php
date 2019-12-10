<?php

namespace App\Http\Controllers\Modals;

use App\Http\Controllers\Controller;
use App\Http\Requests\Income\Customer as Request;
use App\Models\Auth\User;
use App\Models\Income\Customer;
use App\Models\Income\Invoice;
use App\Models\Income\Revenue;
use App\Models\Setting\Currency;
use App\Utilities\Import;
use App\Utilities\ImportFile;
use Date;
use Illuminate\Http\Request as FRequest;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
        $currencies = Currency::enabled()->pluck('name', 'code');

        $customer_selector = false;

        if (request()->has('customer_selector')) {
            $customer_selector = request()->get('customer_selector');
        }

        $rand = rand();

        $html = view('modals.customers.create', compact('currencies', 'customer_selector', 'rand'))->render();

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

        $customer = Customer::create($request->all());

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
