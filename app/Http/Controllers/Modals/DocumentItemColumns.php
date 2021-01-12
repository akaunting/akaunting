<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Setting as Request;

class DocumentItemColumns extends Controller
{
    public $skip_keys = ['company_id', '_method', '_token', '_prefix', '_template', 'type'];

    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:read-settings-settings')->only('index', 'edit');
        $this->middleware('permission:update-settings-settings')->only('update', 'enable', 'disable');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Contact  $customer
     *
     * @return Response
     */
    public function edit()
    {
        $type = request()->get('type', 'invoice');

        $item_names = [
            'settings.invoice.item' => trans('settings.' . $type . '.item'),
            'settings.invoice.product' => trans('settings.' . $type . '.product'),
            'settings.invoice.service' =>  trans('settings.' . $type . '.service'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $price_names = [
            'settings.invoice.price' => trans('settings.' . $type . '.price'),
            'settings.invoice.rate' => trans('settings.' . $type . '.rate'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $quantity_names = [
            'settings.invoice.quantity' => trans('settings.' . $type . '.quantity'),
            'custom' => trans('settings.invoice.custom'),
        ];

        $payment_terms = [
            '0' => trans('settings.invoice.due_receipt'),
            '15' => trans('settings.invoice.due_days', ['days' => 15]),
            '30' => trans('settings.invoice.due_days', ['days' => 30]),
            '45' => trans('settings.invoice.due_days', ['days' => 45]),
            '60' => trans('settings.invoice.due_days', ['days' => 60]),
            '90' => trans('settings.invoice.due_days', ['days' => 90]),
        ];

        $html = view('modals.documents.item_columns', compact(
            'type',
            'item_names',
            'price_names',
            'quantity_names',
            'payment_terms'
        ))->render();

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
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $fields = $request->all();
        $prefix = $request->get('_prefix', 'invoice');
        $company_id = $request->get('company_id');

        if (empty($company_id)) {
            $company_id = session('company_id');
        }

        foreach ($fields as $key => $value) {
            $real_key = $prefix . '.' . $key;

            // Don't process unwanted keys
            if (in_array($key, $this->skip_keys)) {
                continue;
            }

            setting()->set($real_key, $value);
        }

        // Save all settings
        setting()->save();

        $message = trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]);

        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('settings.invoice.edit'),
        ];

        flash($message)->success();

        return response()->json($response);
    }
}
