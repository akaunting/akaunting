<?php

namespace App\Http\Controllers\Settings;

use Akaunting\Money\Currency as MoneyCurrency;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Currency as Request;
use App\Models\Banking\Account;
use App\Models\Setting\Currency;

class Currencies extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $currencies = Currency::collect();

        return view('settings.currencies.index', compact('currencies'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect('settings/currencies');
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
        $codes = array();
        $currencies = MoneyCurrency::getCurrencies();
        foreach ($currencies as $key => $item) {
            // Don't show if already available
            if (in_array($key, $current)) {
                continue;
            }

            $codes[$key] = $key;
        }

        return view('settings.currencies.create', compact('codes'));
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
        // Force the rate to be 1 for default currency
        if ($request['default_currency']) {
            $request['rate'] = '1';
        }

        Currency::create($request->all());

        // Update default currency setting
        if ($request['default_currency']) {
            setting()->set('general.default_currency', $request['code']);
            setting()->save();
        }

        $message = trans('messages.success.added', ['type' => trans_choice('general.currencies', 1)]);

        flash($message)->success();

        return redirect('settings/currencies');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Currency  $currency
     *
     * @return Response
     */
    public function edit(Currency $currency)
    {
        // Get current currencies
        $current = Currency::pluck('code')->toArray();

        // Prepare codes
        $codes = array();
        $currencies = MoneyCurrency::getCurrencies();
        foreach ($currencies as $key => $item) {
            // Don't show if already available
            if (($key != $currency->code) && in_array($key, $current)) {
                continue;
            }

            $codes[$key] = $key;
        }

        // Set default currency
        $currency->default_currency = ($currency->code == setting('general.default_currency')) ? 1 : 0;

        return view('settings.currencies.edit', compact('currency', 'codes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Currency  $currency
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Currency $currency, Request $request)
    {
        // Check if we can disable or change the code
        if (!$request['enabled'] || ($currency->code != $request['code'])) {
            $relationships = $this->countRelationships($currency, [
                'accounts' => 'accounts',
                'customers' => 'customers',
                'invoices' => 'invoices',
                'revenues' => 'revenues',
                'bills' => 'bills',
                'payments' => 'payments',
            ]);

            if ($currency->code == setting('general.default_currency')) {
                $relationships[] = strtolower(trans_choice('general.companies', 1));
            }
        }

        if (empty($relationships)) {
            // Force the rate to be 1 for default currency
            if ($request['default_currency']) {
                $request['rate'] = '1';
            }

            $currency->update($request->all());

            // Update default currency setting
            if ($request['default_currency']) {
                setting()->set('general.default_currency', $request['code']);
                setting()->save();
            }

            $message = trans('messages.success.updated', ['type' => trans_choice('general.currencies', 1)]);

            flash($message)->success();

            return redirect('settings/currencies');
        } else {
            $message = trans('messages.warning.disable_code', ['name' => $currency->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();

            return redirect('settings/currencies/' . $currency->id . '/edit');
        }
    }

    /**
     * Enable the specified resource.
     *
     * @param  Currency  $currency
     *
     * @return Response
     */
    public function enable(Currency $currency)
    {
        $currency->enabled = 1;
        $currency->save();

        $message = trans('messages.success.enabled', ['type' => trans_choice('general.currencies', 1)]);

        flash($message)->success();

        return redirect()->route('currencies.index');
    }

    /**
     * Disable the specified resource.
     *
     * @param  Currency  $currency
     *
     * @return Response
     */
    public function disable(Currency $currency)
    {
        $relationships = $this->countRelationships($currency, [
            'accounts' => 'accounts',
            'customers' => 'customers',
            'invoices' => 'invoices',
            'revenues' => 'revenues',
            'bills' => 'bills',
            'payments' => 'payments',
        ]);

        if ($currency->code == setting('general.default_currency')) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));
        }

        if (empty($relationships)) {
            $currency->enabled = 0;
            $currency->save();

            $message = trans('messages.success.disabled', ['type' => trans_choice('general.currencies', 1)]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.disabled', ['name' => $currency->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();
        }

        return redirect()->route('currencies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Currency  $currency
     *
     * @return Response
     */
    public function destroy(Currency $currency)
    {
        $relationships = $this->countRelationships($currency, [
            'accounts' => 'accounts',
            'customers' => 'customers',
            'invoices' => 'invoices',
            'revenues' => 'revenues',
            'bills' => 'bills',
            'payments' => 'payments',
        ]);

        if ($currency->code == setting('general.default_currency')) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));
        }

        if (empty($relationships)) {
            $currency->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('general.currencies', 1)]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.deleted', ['name' => $currency->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();
        }

        return redirect('settings/currencies');
    }

    public function currency()
    {
        $json = new \stdClass();

        $code = request('code');

        // Get currency object
        $currency = Currency::where('code', $code)->first();

        // it should be integer for amount mask
        $currency->precision = (int) $currency->precision;

        return response()->json($currency);
    }

    public function config()
    {
        $json = new \stdClass();

        $code = request('code');

        if ($code) {
            $currency = config('money.' . $code);
            
            $currency['rate'] = isset($currency['rate']) ? $currency['rate'] : null;
            $currency['symbol_first'] = $currency['symbol_first'] ? 1 : 0;

            $json = (object) $currency;
        }

        return response()->json($json);
    }
}
