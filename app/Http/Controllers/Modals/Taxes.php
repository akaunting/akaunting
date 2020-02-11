<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Tax as Request;
use App\Jobs\Setting\CreateTax;

class Taxes extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-settings-taxes')->only(['create', 'store']);
        $this->middleware('permission:read-settings-taxes')->only(['index', 'edit']);
        $this->middleware('permission:update-settings-taxes')->only(['update', 'enable', 'disable']);
        $this->middleware('permission:delete-settings-taxes')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $types = [
            'normal' => trans('taxes.normal'),
            'inclusive' => trans('taxes.inclusive'),
            'compound' => trans('taxes.compound'),
        ];

        $tax_selector = false;

        if (request()->has('tax_selector')) {
            $tax_selector = request()->get('tax_selector');
        }

        $rand = rand();

        $html = view('modals.taxes.create', compact('types', 'tax_selector', 'rand'))->render();

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

        $response = $this->ajaxDispatch(new CreateTax($request));

        if ($response['success']) {
            $response['message'] = trans('messages.success.added', ['type' => trans_choice('general.taxes', 1)]);
        }

        return response()->json($response);
    }
}
