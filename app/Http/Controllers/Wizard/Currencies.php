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
    public function edit()
    {
        if (setting('general.wizard', false)) {
            //return redirect('/');
        }

        $currencies = Currency::all();

        return view('wizard.currencies.edit', compact('currencies'));
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
}
