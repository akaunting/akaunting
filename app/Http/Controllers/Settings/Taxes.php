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

        return view('settings.taxes.index', compact('taxes', 'rates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('settings.taxes.create');
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
        $tax->update($request->all());

        $message = trans('messages.success.updated', ['type' => trans_choice('general.tax_rates', 1)]);

        flash($message)->success();

        return redirect('settings/taxes');
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
        $canDelete = $tax->canDelete();

        if ($canDelete === true) {
            $tax->delete();

            $message = trans('messages.success.deleted', ['type' => trans_choice('general.taxes', 1)]);

            flash($message)->success();
        } else {
            $text = array();

            if (isset($canDelete['items'])) {
                $text[] = '<b>' . $canDelete['items'] . '</b> ' . trans_choice('general.items', ($canDelete['items'] > 1) ? 2 : 1);
            }

            if (isset($canDelete['bills'])) {
                $text[] = '<b>' . $canDelete['bills'] . '</b> ' . trans_choice('general.bills', ($canDelete['bills'] > 1) ? 2 : 1);
            }

            if (isset($canDelete['invoices'])) {
                $text[] = '<b>' . $canDelete['invoices'] . '</b> ' . trans_choice('general.items', ($canDelete['invoices'] > 1) ? 2 : 1);
            }

            $message = trans('messages.warning.deleted', ['type' => trans_choice('general.taxes', 1), 'text' => implode(', ', $text)]);

            flash($message)->warning();
        }

        return redirect('settings/taxes');
    }
}
