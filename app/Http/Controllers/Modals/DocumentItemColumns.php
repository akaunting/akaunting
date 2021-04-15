<?php

namespace App\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Setting as Request;
use App\Traits\Documents;

class DocumentItemColumns extends Controller
{
    use Documents;

    public $skip_keys = ['company_id', '_method', '_token', '_template', 'type'];

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

        $item_name             = setting($this->getSettingKey($type, 'item_name'));
        $item_name_input       = setting($this->getSettingKey($type, 'item_name_input'));
        $price_name            = setting($this->getSettingKey($type, 'price_name'));
        $price_name_input      = setting($this->getSettingKey($type, 'price_name_input'));
        $quantity_name         = setting($this->getSettingKey($type, 'quantity_name'));
        $quantity_name_input   = setting($this->getSettingKey($type, 'quantity_name_input'));
        $hide_item_name        = setting($this->getSettingKey($type, 'hide_item_name'));
        $hide_item_description = setting($this->getSettingKey($type, 'hide_item_description'));
        $hide_quantity         = setting($this->getSettingKey($type, 'hide_quantity'));
        $hide_price            = setting($this->getSettingKey($type, 'hide_price'));
        $hide_amount           = setting($this->getSettingKey($type, 'hide_amount'));

        $html = view('modals.documents.item_columns', compact(
            'type',
            'item_names',
            'price_names',
            'quantity_names',
            'payment_terms',
            'item_name',
            'item_name_input',
            'price_name',
            'price_name_input',
            'quantity_name',
            'quantity_name_input',
            'hide_item_name',
            'hide_item_description',
            'hide_quantity',
            'hide_price',
            'hide_amount',
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
        $type = $request->get('type', 'invoice');
        $company_id = $request->get('company_id');

        if (empty($company_id)) {
            $company_id = company_id();
        }

        foreach ($fields as $key => $value) {
            $real_key = $this->getSettingKey($type, $key);

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
