<?php

namespace App\Http\Controllers\Wizard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Tax as Request;
use App\Models\Setting\Tax;

class Taxes extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        Tax::create($request->all());

        $message = trans('messages.success.added', ['type' => trans_choice('general.tax_rates', 1)]);

        flash($message)->success();

        return redirect('settings/taxes');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Tax  $tax
     *
     * @return Response
     */
    public function edit(Tax $tax)
    {
        return view('settings.taxes.edit', compact('tax'));
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

    /**
     * Enable the specified resource.
     *
     * @param  Tax  $tax
     *
     * @return Response
     */
    public function enable(Tax $tax)
    {
        $tax->enabled = 1;
        $tax->save();

        $message = trans('messages.success.enabled', ['type' => trans_choice('general.tax_rates', 1)]);

        flash($message)->success();

        return redirect()->route('taxes.index');
    }

    /**
     * Disable the specified resource.
     *
     * @param  Tax  $tax
     *
     * @return Response
     */
    public function disable(Tax $tax)
    {
        $relationships = $this->countRelationships($tax, [
            'items' => 'items',
            'invoice_items' => 'invoices',
            'bill_items' => 'bills',
        ]);

        if (empty($relationships)) {
            $tax->enabled = 0;
            $tax->save();

            $message = trans('messages.success.disabled', ['type' => trans_choice('general.tax_rates', 1)]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.disabled', ['name' => $tax->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();

            return redirect()->route('taxes.index');
        }

        return redirect()->route('taxes.index');
    }
}
