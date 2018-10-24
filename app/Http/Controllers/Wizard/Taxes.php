<?php

namespace App\Http\Controllers\Wizard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Tax as Request;
use App\Models\Setting\Tax;

class Taxes extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  Tax  $tax
     *
     * @return Response
     */
    public function edit()
    {
        $tax = [];

        return view('wizard.taxes.edit', compact('tax'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Tax  $tax
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Tax $tax, Request $request)
    {
        $relationships = $this->countRelationships($tax, [
            'items' => 'items',
            'invoice_items' => 'invoices',
            'bill_items' => 'bills',
        ]);

        if (empty($relationships) || $request['enabled']) {
            $tax->update($request->all());

            $message = trans('messages.success.updated', ['type' => trans_choice('general.tax_rates', 1)]);

            flash($message)->success();

            return redirect('settings/taxes');
        } else {
            $message = trans('messages.warning.disabled', ['name' => $tax->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();

            return redirect('settings/taxes/' . $tax->id . '/edit');
        }
    }
}
