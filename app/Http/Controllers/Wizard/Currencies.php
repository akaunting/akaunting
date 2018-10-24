<?php

namespace App\Http\Controllers\Wizard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Currency as Request;
use App\Models\Banking\Account;
use App\Models\Setting\Currency;

class Currencies extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  Currency  $currency
     *
     * @return Response
     */
    public function index()
    {
        if (setting('general.wizard', false)) {
            return redirect('/');
        }

        $currencies = Currency::all();

        return view('wizard.currencies.index', compact('currencies'));
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
        if (setting('general.wizard', false)) {
            return redirect('/');
        }

        $html = view('wizard.currencies.edit', compact('currency'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
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
            $message = trans('messages.warning.disabled', ['name' => $currency->name, 'text' => implode(', ', $relationships)]);

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

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => $currency,
        ]);
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

            return response()->json([
                'success' => true,
                'error' => false,
                'message' => $message,
                'data' => $currency,
            ]);
        } else {
            $message = trans('messages.warning.disabled', ['name' => $currency->name, 'text' => implode(', ', $relationships)]);

            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $message,
                'data' => $currency,
            ]);
        }
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

            return response()->json([
                'success' => true,
                'error' => false,
                'message' => $message,
                'data' => $currency,
            ]);
        } else {
            $message = trans('messages.warning.deleted', ['name' => $currency->name, 'text' => implode(', ', $relationships)]);

            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $message,
                'data' => $currency,
            ]);
        }
    }
}
