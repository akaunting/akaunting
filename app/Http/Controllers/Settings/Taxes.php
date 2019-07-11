<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Tax as Request;
use App\Models\Setting\Tax;

class Taxes extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $taxes = Tax::collect();

        $types = [
            'normal' => trans('taxes.normal'),
            'inclusive' => trans('taxes.inclusive'),
            'compound' => trans('taxes.compound'),
        ];

        return view('settings.taxes.index', compact('taxes', 'types'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect('settings/taxes');
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

        return view('settings.taxes.create', compact('types'));
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
        $types = [
            'normal' => trans('taxes.normal'),
            'inclusive' => trans('taxes.inclusive'),
            'compound' => trans('taxes.compound'),
        ];

        return view('settings.taxes.edit', compact('tax', 'types'));
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
        }

        return redirect()->route('taxes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tax  $tax
     *
     * @return Response
     */
    public function destroy(Tax $tax)
    {
        $relationships = $this->countRelationships($tax, [
            'items' => 'items',
            'invoice_items' => 'invoices',
            'bill_items' => 'bills',
        ]);

        if (empty($relationships)) {
            $tax->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('general.taxes', 1)]);

            flash($message)->success();
        } else {
            $message = trans('messages.warning.deleted', ['name' => $tax->name, 'text' => implode(', ', $relationships)]);

            flash($message)->warning();
        }

        return redirect('settings/taxes');
    }
}
