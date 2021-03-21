<?php

namespace App\Http\Controllers\Modals;

use Akaunting\Money\Currency as MoneyCurrency;
use App\Abstracts\Http\Controller;
use App\Jobs\Setting\CreateCurrency;
use App\Models\Setting\Currency;
use App\Http\Requests\Setting\Currency as Request;

class Currencies extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-settings-currencies')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-settings-currencies')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-settings-currencies')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-settings-currencies')->only('destroy');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get current currencies
        $current = Currency::pluck('code')->toArray();

        // Prepare codes
        $codes = [];
        $currencies = MoneyCurrency::getCurrencies();

        foreach ($currencies as $key => $item) {
            // Don't show if already available
            if (in_array($key, $current)) {
                continue;
            }

            $codes[$key] = $key;
        }

        $html = view('modals.currencies.create', compact('codes'))->render();

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
        $currency = config('money.' . $request->get('code'));

        $request['precision'] = (int) $currency['precision'];
        $request['symbol'] = $currency['symbol'];
        $request['symbol_first'] = $currency['symbol_first'] ? 1 : 0;
        $request['decimal_mark'] = $currency['decimal_mark'];
        $request['thousands_separator'] = $currency['thousands_separator'];

        $request['enabled'] = 1;
        $request['default_currency'] = false;

        $response = $this->ajaxDispatch(new CreateCurrency($request->all()));

        if ($response['success']) {
            $response['message'] = trans('messages.success.added', ['type' => trans_choice('general.currencies', 1)]);
        }

        return response()->json($response);
    }
}
